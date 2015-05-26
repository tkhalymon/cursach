#!/bin/bash
cat $1 | ./a.out > user_res.txt && killall sleep &
sleep $2
killall a.out 2> timelimit.txt