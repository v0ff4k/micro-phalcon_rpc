Install Phalcon, Docker,
-----------------------

using phalcon micro
create   ***site*** and ***user*** 

> web root is   site/index.php 

will help http://phalcondoc.p2hp.com/ru/3.1/application-micro

Must be
-------
* Dockerfile
* site accessed via post admin:admin
* site  accessed via 80port
* users without external ports ports
* user accessed only via json-rpc


CLi commands
------------
```bash


### install(+remove old) DOCKER

sudo apt-get remove docker docker-engine docker.io containerd runc

apt-get -y install docker.io

apt search docker-machine
#  - but nothing here !!!!
### - so run this
base=https://github.com/docker/machine/releases/download/v0.16.0 &&
  curl -L $base/docker-machine-$(uname -s)-$(uname -m) >/tmp/docker-machine &&
  sudo mv /tmp/docker-machine /usr/local/bin/docker-machine &&
  chmod +x /usr/local/bin/docker-machine

# or /usr/bin/docker-machine
# or chmod +x /usr/bin/docker-machine

### - installing docker-compose

sudo curl -L "https://github.com/docker/compose/releases/download/1.24.1/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
# or sudo curl -L "https://github.com/docker/compose/releases/download/1.24.1/docker-compose-$(uname -s)-$(uname -m)" -o /usr/bin/docker-compose
# sudo chmod +x /usr/bin/docker-compose


### - install phalcon-devtools
cd ~
git clone https://github.com/phalcon/phalcon-devtools.git
cd phalcon-devtools
ln -s $(pwd)/phalcon /usr/bin/phalcon
chmod ugo+x /usr/bin/phalcon
phalcon commands help


#optional - end

git clone https://github.com/v0ff4k/micro-phalcon_rpc.git
cd micro-phalcon_rpc
docker-compose up

# Check your localhost OR 127.0.0.1 in browser. Profit!

