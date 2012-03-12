# Crust Framework
# Generates views
#
# @version 0.0.1
# @author Ahmet Ozisik

import threading, msvcrt
import sys
import os

print "Crust Framework Generate v0.0.3 (Python)\n"

def createFile(file_path, content):
	f = open(file_path, 'w')
	f.write(content)
	f.close()
	

def readInput(caption, default='', timeout = 999):
    class KeyboardThread(threading.Thread):
        def run(self):
            self.timedout = False
            self.input = ''
            while True:
                if msvcrt.kbhit():
                    chr = msvcrt.getche()
                    if ord(chr) == 13:
                        break
                    elif ord(chr) >= 32:
                        self.input += chr
                if len(self.input) == 0 and self.timedout:
                    break    
				

    sys.stdout.write('%s:'%(caption));
    result = default
    it = KeyboardThread()
    it.start()
    it.join(timeout)
    it.timedout = True
    if len(it.input) > 0:
        # wait for rest of input
        it.join()
        result = it.input
    print ''  # needed to move to next line
    return result

# and some examples of usage
controller_name = ''
while (controller_name == '' or controller_name.isdigit()):
	controller_name = readInput('Enter controller name') 
	
views_dir		 = 'app/views/'+controller_name

while 1:

  view_name = ''
  while (view_name == '' or view_name.isdigit()):
    view_name = readInput('Enter view name for "'+controller_name+'" (q=exit)') 

  if view_name == 'q':
    exit
  
  tpl  = "<h3>"+view_name+"</h3>"
  
  view_path = views_dir+'/'+view_name+'.tpl'

  createFile(view_path, tpl)

  print  "\n" + view_path + " was created"