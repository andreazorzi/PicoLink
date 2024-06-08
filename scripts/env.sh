# Get variables
type=$1
key=$2

# Fill password to the required length
while ((${#key} < 32)); do 
	key+='X'
done

# Set array of env file types and iterate
array=( "" ".production" ".portainer" )

for i in "${array[@]}"
do
	# If type encrypt and plain file exists or type decrypt and encrypted file exists
	if ([ "$type" == "encrypt" ] && test -f ".env$i") || ([ "$type" == "decrypt" ] && test -f ".env$i.encrypted"); then
		# run artisan command
		php artisan env:$type --key=$key --env=${i:1} --force
	fi
done