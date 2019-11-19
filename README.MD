
TODO: make easer logic
---------------------



-----------------------
  The task:
-----------------------

using phalcon micro
create   ***site*** and ***user*** 

> web root is only  site/public/index.php 
> backend with DB  is users/public/index.php 

will help http://phalcondoc.p2hp.com/ru/3.1/application-micro

Must be
-------
* Dockerfile for each part

* site - is web, frontend
* site accessed via forms with POST  admin:admin
* site  accessed via 80port
* site send jsonRPC request to users

* users - is closed, backend
* users without external ports ports
* user accessed only via jsonRPC


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
## or rebuild  older failed container
docker-compose -f docker-compose.yml up -d --build

# Check your localhost OR 127.0.0.1 in browser. Profit!

### synh your databases with  migration files
phalcon gen-migration

#for others flags see:
phalcon gen-migration --help

# show  network status
docker network ls

# and opened ports
netstat -aop TCP | grep /docker-

# show processes in the docker
docker ps
docker stop|start|restart|kill processId

# list and remove some useless  PROCESS  in docker 
docker ps -a
docker rm processId anotherProcId

# list and remove some useless  IMAGE  in docker
docker images
docker rmi imageId anotherImgId

### others
`` https://webhamster.ru/mytetrashare/index/mtb192/15159984130x47p7lgyi ``
