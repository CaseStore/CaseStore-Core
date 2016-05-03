Run from root git folder:

    docker run --name casestoretestsmysql -e MYSQL_ROOT_PASSWORD=password -e MYSQL_DATABASE=testing -e MYSQL_USER=testing -e MYSQL_PASSWORD=password -d mysql:5.7
    docker build -f docker/container/tests/Dockerfile -t casestoretests .
    docker run --name casestoretests --link casestoretestsmysql:mysql casestoretests

To Re-Run:

    docker rm -f casestoretests
    docker rmi casestoretests
    docker build -f docker/container/tests/Dockerfile -t casestoretests .
    docker run --name casestoretests --link casestoretestsmysql:mysql casestoretests

And Clean Up:

    docker rm -f casestoretestsmysql
    docker rm -f casestoretests
    docker rmi casestoretests
