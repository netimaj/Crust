# Crust Framework
# Generates controllers
#
# @version 0.0.3
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
	
controller_path  = 'app/controllers/'+controller_name+'.controller.php'
views_dir		 = 'app/views/'+controller_name
index_view		 = views_dir + "/index.tpl"

code = "<?php\n/*\n  " + controller_name.title() + " Controller\n*/\nclass " + controller_name.title() + "Controller extends ApplicationController\n{\n\tpublic function index(){}\n}"
tpl  = "<h3>"+controller_name+"</h3>"

createFile(controller_path, code)


print  "\n" + controller_path + " was created"

if not os.path.exists(views_dir):
    os.makedirs(views_dir)

print "\n" + views_dir + " was created"

createFile(index_view, tpl)

print "\n" + index_view + " was created"

raw_input('Press enter to quit.')
