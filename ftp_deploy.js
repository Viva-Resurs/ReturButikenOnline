// Upload public/ to a ftp-server

var config = require('./ftp.json');

var FtpDeploy = require('ftp-deploy');
var ftpDeploy = new FtpDeploy();

ftpDeploy.deploy(config, function(err) {
    if (err) console.log(err)
    else console.log('finished');
});
