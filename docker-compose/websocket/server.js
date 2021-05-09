var express = require('express');
var expressWs = require('express-ws');
var pty = require('node-pty');
var cors = require('cors');

function startServer() {
    var app = express();
    expressWs(app);

    var terminals = {},
        logs = {};

    app.use(cors());
    app.use(express.json());
    app.use(express.urlencoded({ extended: true }));

    app.post('/terminals', (req, res) => {
        const env = Object.assign({}, process.env);
        env['COLORTERM'] = 'truecolor';
        var cols = parseInt(req.query.cols),
            rows = parseInt(req.query.rows),
            term = pty.spawn( 'bash', [], {
                name: 'xterm-256color',
                cols: cols || 80,
                rows: rows || 24,
                cwd: env.PWD,
                env: env,
                encoding: 'utf8'
            });

        console.log('Created terminal with PID: ' + term.pid);
        terminals[term.pid] = term;
        logs[term.pid] = '';
        term.on('data', function(data) {
            logs[term.pid] += data;
        });

        res.send(term.pid.toString());
        res.end();
    });

    app.post('/terminals/:pid/data', function (req, res) {
        let term = terminals[parseInt(req.body.pid)];
        console.log('Sending data to terminal ' + term.pid);
        console.log('The data ' + req.body.data);
        term.write(req.body.data)

    });

    app.post('/terminals/:pid/size', (req, res) => {
        let pid = parseInt(req.params.pid),
            cols = parseInt(req.query.cols),
            rows = parseInt(req.query.rows),
            term = terminals[pid];

        term.resize(cols, rows);
        console.log('Resized terminal ' + pid + ' to ' + cols + ' cols and ' + rows + ' rows.');
        res.end();
    });

    app.ws('/terminals/:pid', function (ws, req) {
        let term = terminals[parseInt(req.params.pid)];
        console.log('Connected to terminal ' + term.pid);
        ws.send(logs[term.pid]);

        // string message buffering
        function buffer(socket, timeout) {
            let s = '';
            let sender = null;
            return (data) => {
                s += data;
                if (!sender) {
                    sender = setTimeout(() => {
                        socket.send(s);
                        s = '';
                        sender = null;
                    }, timeout);
                }
            };
        }

        const send = buffer(ws, 5);

        term.on('data', function(data) {
            try {
                send(data);
            } catch (ex) {
                // The WebSocket is not open, ignore
            }
        });
        ws.on('message', function(msg) {
            term.write(msg);
        });
        ws.on('close', function () {
            term.kill();
            console.log('Closed terminal ' + term.pid);
            // Clean things up
            delete terminals[term.pid];
            delete logs[term.pid];
        });
    });



    var port =  8999,
        host =  '0.0.0.0';

    console.log('App listening to http://0.0.0.0:' + port);
    app.listen(port, host);
}

startServer()
