<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
</head>
<body>
<h1>Facture de la commande Numéro {{ $order->numberOrder}} de {{$order->user->name}}</h1>
<p>Détails de la commande :</p>
<p>Produits : {{$order->burgers}}</p>
<p>Date: {{ $order->created_at }}</p>
<!-- Add more details as needed -->
</body>
</html>
