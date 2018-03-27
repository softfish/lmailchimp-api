# lmailchimp-api

Simple package for MailChimp's List and member management.

## Package Functions Summary:

1. Create, update and remove a list
2. Add members to a list
3. Update members within a list
4. Remove member from a list

## Developer Note
I am going to fine tune this a bit more first before I upload it to packagist.org.

To use this package make sure you have obtained API key from your MailChimp account and set it into the .env file from laravel.

```
LMAILCHIMP_API_KEY=[your-api-key]
```
## API Manual

Below are the information on how you can use for the API methods. I have included some request samples for each API.

### Get existing lists from your MailChimp Account
API Endpoint:
```
/api/lmailchimp/lists/
```
Method: GET

Example Json Response:
```
{
    "success": true,
    "lists": [
        {
            "id": "844a96be1c",
            "web_id": 416057,
            "name": "Developer Test (Edit)",
            "contact": {
                "company": "testing company ltd",
                "address1": "46 Budd Street",
                "address2": "",
                "city": "Collingwood",
                "state": "VIC",
                "zip": "3066",
                "country": "US",
                "phone": "0389237658"
            },
            "permission_reminder": "You received this email because we are doing testing",
              :
        },
        {
            "id": "86c07e8fb9",
            "web_id": 177061,
            "name": "Developers",
            "contact": {
                "company": "Integrated Node",
                "address1": "PO Box 291",
                "address2": "",
                "city": "CANNINGTON",
                "state": "WA",
                "zip": "6987",
                "country": "AU",
                "phone": ""
            },
            "permission_reminder": "You are receiving this email because you are one of the developers list in our developer list. If you found this information is incorrect, please kindly report this to our administrator.",
              :
        }
    ],
    "total_items": 2
}
```
### Create a new List in MailChimp
API Endpoint:
```
/api/lmailchimp/lists
```
Method: POST

Example for Request
```
{
  "name": "Developer Test",
  "contact.company": "testing company ltd",
  "contact.address1": "46 Test Street",
  "contact.address2": "",
  "contact.city": "Collingwood",
  "contact.state": "VIC",
  "contact.zip": "3066",
  "contact.country": "Australia",
  "contact.phone": "0389237658",
  "permission_reminder": "You received this email because we are doing testing",
  "use_archive_bar": false,
  "campaign_defaults.from_name": "Fei Kwok",
  "campaign_defaults.from_email": "softfishtest@gmail.com",
  "campaign_defaults.subject": "Test List Laravel Package Subject",
  "campaign_defaults.language": "English",
  "notify_on_subscribe": "",
  "notify_on_unsubscribe": "",
  "email_type_option": false,
  "visibility": "prv"
}
```
Response JSON 
```
{
    "success": true,
    "message": "New list has been created.",
    "new_list": {
        "id": "c6934bea55",
        "web_id": 416165,
          :
    }
}
```
### Update existing list info.
API Endpoint:
```
/api/lmailchimp/lists/[list id]
```
Method: PATCH/PUT

The request JSON structure and attributes are the same as list creation. The response are the same, just the message is different.

### Delete an existing list info.
API Endpoint:
```
/api/lmailchimp/lists/[list_id]
```
Method: DELETE

### Add new member to the list
API Endpoint:
```
/api/lmailchimp/lists/[list id]/members
```
Method: POST

Example of JSON Request
```
{
  "email_address": "test@email.com",
  "email_type": "html",
  "status": "subscribed",
  "language": "English",
  "vip": true,
  "ip_signup": "122.321.12.1",
  "timestamp_signup": "2018-03-21 22:21:21"
}
```

### Update an existing member info
API Endpoint:
```
/api/lmailchimp/lists/[list id]/members
```
Method: PATCH/PUT

Request are the same as the creation for new member above.

### Remove member from a list

API Endpoint:
```
/api/lmailchimp/lists/[list id]/members
```
Method: DELETE

All you need for the delete member function is to include the email in the request.

Example Request JSON
```
{
  "email": "test@email.com"
}
```
