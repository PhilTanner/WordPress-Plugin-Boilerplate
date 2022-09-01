#!/bin/bash
# Ask the user for login details
read -p '    Your WordPress username: ' wpusername
read -p '     Plugin name (not slug): ' pluginname
read -p '             Plugin website: ' pluginwebsite
read -p '                 Your email: ' useremail
read -p 'Your (or your company) name: ' username
read -p '               Your Website: ' userwebsite

# Convert our plugin name to a safe one for variables/filenames etc.
pluginsafename=${pluginname//[^[:alnum:][:space:]]/}
# Generate a list of every possible combination (we won't use most of them).
lc=${pluginsafename,,}
lch=${lc//[[:space:]]/-}
lcu=${lc//[[:space:]]/_}
tc=$(echo $lc | sed 's/.*/\L&/; s/[a-z]*/\u&/g')
tch=${tc//[[:space:]]/-}
tcu=${tc//[[:space:]]/_}
uc=${pluginsafename^^}
uch=${uc//[[:space:]]/-}
ucu=${uc//[[:space:]]/_}

echo
echo This script will now update the files in this plugin directory.
read -r -p "Are you sure you want to continue? [y/N] " response
if [[ "$response" =~ ^([yY][eE][sS]|[yY])$ ]]
then
  echo
  echo Making changes documented in README.md under Installation
  echo "Renaming files..."
  find . -iname "*plugin-name*" -exec bash -c 'mv "$1" "${1//plugin-name/'${lch}'}"' -- {} \;

  echo "Updating plugin variable names..."
  files=$(grep -rl plugin_name * |grep -v plugin-initialisation.sh) && echo $files | xargs sed -i "s,plugin_name,${lcu},g"
  files=$(grep -rl plugin-name * |grep -v plugin-initialisation.sh) && echo $files | xargs sed -i "s,plugin-name,${lch},g"
  files=$(grep -rl Plugin_Name * |grep -v plugin-initialisation.sh) && echo $files | xargs sed -i "s,Plugin_Name,${tcu},g"
  files=$(grep -rl PLUGIN_NAME_ * |grep -v plugin-initialisation.sh) && echo $files | xargs sed -i "s,PLUGIN_NAME_,${ucu}_,g"

  echo "Updating URLs in files..."
  # Not using something like the below, as that will touch every file, regardless
  # of if it contains the match and that will confuse our Git history - so
  # instead we use grep to find only files containing our match in first place
  #find ./ -type f -not -path '*/\.*' -exec sed -i "s,Your Name <email@example.com>,${username} <${useremail}>,g" {} \;
  files=$(grep -rl http\:\/\/example\.com * |grep -v rename.sh) && echo $files | xargs sed -i "s,http://example.com/plugin-name-uri/,${pluginwebsite},g"
  files=$(grep -rl http\:\/\/example\.com * |grep -v rename.sh) && echo $files | xargs sed -i "s,http://example.com,${userwebsite},g"

  echo "Updating references to you"
  files=$(grep -rl \@example\.com * |grep -v rename.sh) && echo $files | xargs sed -i "s,Your Name <email@example.com>,${username} <${useremail}>,g"

  echo "Updating WordPress Contributors..."
  sed -i "s,(this should be a list of wordpress.org userid's),${wpusername},g" README.txt

  echo
  echo Complete.
  echo "You will need to rename this current directory to ${lch} manually in"
  echo "order to meet WordPress plugin development best practices."
  echo
  echo You also need to update the README.txt appropriately.
  echo And it would be wise to create a base git commit at this point.

else
  echo
  echo Aborting. No changes were made.
fi

echo
read -n 1 -s -r -p "Press any key to continue"
