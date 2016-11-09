#!/bin/sh

echo "starting cron reset..."

cat /home/o110648/public_html/www.coderace.co.uk/monitor/reset_test.sql | mysql -h localhost -u o110648_cr -pC0d3rac3 o110648_coderace_games