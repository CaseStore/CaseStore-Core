FROM debian:8.4

ADD . /app

RUN apt-get update
RUN apt-get install -y  php5-cli php5-mysql phpunit wget

RUN mkdir /home/bin
RUN cd /home/bin && wget https://getcomposer.org/composer.phar

RUN cd /app && php /home/bin/composer.phar  install --no-scripts

RUN cp /app/docker/container/tests/parameters_test.yml /app/app/config/parameters_test.yml

CMD cd /app && phpunit -c app/phpunit.xml.dist
