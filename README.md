# Cisco cli
A php tool that convert the short form of a cisco cli command into the complete

### Source code structure
- index.php -> the page to to try the program
- cli.php -> the file with all the functions that convert  
  > to extend the text call the extend() function from this file and pass as argument the text
- commands.json -> the file with all the commands, keywords, and regular expression that the cli.php use

### How it works
- split all the lines
- split all the words for each line
- the first word is considered as command, the other as keyword, interface, ip address or number
- compare each word with the ones inside the commands.json file
- return:
> - the extended word if founded
> - the word inside two * if nothing as been founded
> - the same word if founded more than one, is exactly as one or if it's an ip address or a number