import braintree
import json
import os.path
from inspect import getmembers
from pprint import pprint
from datetime import date, timedelta

braintree.Configuration.configure(
    braintree.Environment.Sandbox,
    "23nd25g4kn7gnqbb",
    "8552x2ym5bvhsycp",
    "17f3279171d4fd90ee9cd5256be17abf"
)

lookup = braintree.Transaction.search(
  braintree.TransactionSearch.created_at.between(
    (date.today() - timedelta(days=1)),
    date.today()
  )
)


for i in lookup._ResourceCollection__ids:

    transaction = braintree.Transaction.find(i)
    print transaction.id
    if transaction.custom_fields and os.path.exists("files/"+transaction.id) == False:

        file = open("files/"+transaction.id, "w")

        contents = json.loads(transaction.custom_fields['cart'])
        for z in contents:
            if z.has_key("total"):
                file.write("Total: $"+z["total"])
            else:
                file.write(z["qty"]+" "+z["itemName"]+"\n")
                file.write("$"+z["price"]+"\n\n")

        file.close()



