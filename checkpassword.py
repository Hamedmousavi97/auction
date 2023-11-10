import hashlib

# Existing hashed password (stored in the database)
hashed_password = '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92'

# Plain password entered by the user
plain_password = '123456'

# Hash the plain password using the same algorithm as before
new_hashed_password = hashlib.sha256(plain_password.encode()).hexdigest()

# Check if the hashes match
if new_hashed_password == hashed_password:
    print("Password is correct.")
else:
    print("Password is incorrect.")
    