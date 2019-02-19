#!/bin/bash

db_username="dbuser"
db_password="secret"
db_name="laravel_database"
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
DIRNAME=${DIR##*/}

docker_mysql="${DIRNAME}_mysql"
docker_elasticsearch="${DIRNAME}_elasticsearch"

# ===== pass arguments ===== #

execution_type=()
while [[ $# -gt 0 ]]
do
key="$1"

case $key in
    -u|--dbuser)
    db_username="$2"
    shift # past argument
    shift # past value
    ;;
    -p|--dbpass)
    db_password="$2"
    shift # past argument
    shift # past value
    ;;
    -d|--dbname)
    db_name="$2"
    shift
    shift
    ;;
    *)    # unknown option
    execution_type+=("$1") # save it in an array for later
    shift # past argument
    ;;
esac
done
set -- "${execution_type[@]}" # restore positional parameters

# ===== pass first argument =====

if [ -z "$execution_type" ]; then
    echo "Positional argument have to be supplied."
    ex_error="no positional argument"
else
    execution_type="$1"
fi

if [[ "$execution_type" != "start" && 
      "$execution_type" != "stop" &&
      "$execution_type" != "reset" &&
      "$execution_type" != "rm" ]]; then
    echo "The positional argument has to be either start, stop, reset or rm."
    ex_error="positional argument invalid"
fi

if [ ! -z "$ex_error" ]; then
    echo "An error occured."
    echo "Execution:"
    echo "$0 {start|stop|reset|rm} [--dbuser|-u <username>] [--dbpass|-p <password>] [--dbname|-d <database name>]"
    exit -1
fi

if [[ "$execution_type" == "stop" || "$execution_type" == "rm" || "$execution_type" == "reset" ]]; then
    echo "Stopping docker containers"
    # Stop mysql container
    if [ "$(docker ps -q -f name=$docker_mysql)" ]; then
        if [ "$(docker ps -aq -f status=running -f name=$docker_mysql)" ]; then
            docker stop $docker_mysql > /dev/null 
            echo "Stopped container '$docker_mysql'."
        fi
    fi
    # Stop elasticsearch container
    if [ "$(docker ps -q -f name=$docker_elasticsearch)" ]; then
        if [ "$(docker ps -aq -f status=running -f name=$docker_elasticsearch)" ]; then
            docker stop $docker_elasticsearch > /dev/null 
            echo "Stopped container '$docker_elasticsearch'."
        fi
    fi
fi

if [[ "$execution_type" == "rm" || "$execution_type" == "reset" ]]; then
    echo "Removing docker containers"
    if [ "$(docker ps -aq -f name=$docker_mysql)" ]; then
        docker rm $docker_mysql > /dev/null
        echo "Removed $docker_mysql"
    fi
    if [ "$(docker ps -aq -f name=$docker_elasticsearch)" ]; then
        docker rm $docker_elasticsearch > /dev/null
        echo "Removed $docker_elasticsearch"
    fi
fi

if [[ "$execution_type" == "start" || "$execution_type" == "reset" ]]; then
    echo "Starting docker containers"
    ## MySQL
    if [ "$(docker ps -aq -f name=$docker_mysql)" ]; then
        if [ "$(docker ps -aq -f status=exited -f name=$docker_mysql)" ]; then
            docker start $docker_mysql > /dev/null
            echo "Started docker container $docker_mysql"
        elif [ "$(docker ps -aq -f status=running -f name=$docker_mysql)" ]; then
            echo "Docker container $docker_mysql is already running."
        fi
    else
        echo "Creating new mysql container ($docker_mysql)."
        docker run \
            --name $docker_mysql \
            -e MYSQL_ROOT_PASSWORD=$db_password \
            -e MYSQL_DATABASE=$db_name \
            -e MYSQL_USER=$db_username \
            -e MYSQL_PASSWORD=$db_password \
            -p "3306:3306" \
            -d mysql:5.7 > /dev/null
        echo "Created a new mysql docker container with credentials:"
        echo "- root password: $db_password"
        echo "- new username: $db_username"
        echo "- password for new user: $db_password"
        echo "- containername: $docker_mysql"
        echo "MySQL port: 3306"
        echo "Created new database: $db_name"
    fi

    ## Elasticsearch
    if [ "$(docker ps -aq -f name=$docker_elasticsearch)" ]; then
        if [ "$(docker ps -aq -f status=exited -f name=$docker_elasticsearch)" ]; then
            docker start $docker_elasticsearch > /dev/null
            echo "Started docker container $docker_elasticsearch"
        elif [ "$(docker ps -aq -f status=running -f name=$docker_elasticsearch)" ]; then
            echo "Docker container $docker_elasticsearch is already running."
        fi
    else
        echo "Creating new elasticsearch container ($docker_elasticsearch)."
        docker run \
            --name $docker_elasticsearch \
            -p "9200:9200" \
            -p "9300:9300" \
            -e "discovery.type=single-node" \
            -d docker.elastic.co/elasticsearch/elasticsearch:6.6.0
        echo "Created a new elastic search docker container."
        echo "Live on http://localhost:9200"
    fi
fi
