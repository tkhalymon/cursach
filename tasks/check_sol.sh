#!/bin/bash
cat test.txt | ./a.out > user_res.txt && killall sleep &
sleep $1
killall a.out 2> timelimit.txt