# apache-logs-colored.py

import sys, re , random
from termcolor import colored ,cprint

# %h %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-agent}i\" %D

while True:
    colors = ['red','green','yellow','blue','magenta','cyan']
    color = random.choice(colors)
    line = sys.stdin.readline()
    
    if line == '':
            break
    print colored(line.rstrip(),color)
    