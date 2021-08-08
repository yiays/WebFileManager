#!/bin/bash
echo -n Enter a username: 
read username
echo -n Enter a password: 
read password

echo -n $username:$password | md5sum | awk -v username=$username '{print username ":" $1}' >> raw/.passwd

echo Done.