<h2>Utilisateur</h4> 

<h4>Créer un utilisateur</h4> 

```raw
http://localhost/e-kaly/api/routes/utilisateur/creer.php
``` 

Body

```json
{	
    "nom": "string", 
    "prenom": "string", 
    "email": "string",
    "mot_passe": "string", 
    "role": "agent|gerant"
}
```

```json
{
    "success": true,
    "message": "Utilisateur créé avec succès."
}
```

<h4>Authentifier un utilisateur</h4>

```raw
http://localhost/e-kaly/api/routes/utilisateur/login.php
```

Body 

```json
{	
    "email": "string",
    "mot_passe": "string"
}
```
Succès 

```json
{
    "success": true,
    "token": "eyJhbGciOi..."
}
```

Echec 

```json
{
    "success": false,
    "message": "Echec d'authentification."
}
```

<h2>Repas</h2>

<h4>Ajouter un repas (gerant)</h4> 

```raw
POST http://localhost/e-kaly/api/routes/repas/ajouter.php
```

Headers

```raw
Content-Type: multipart/form-data
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI...
```

Body 

```raw
multipart/form-data 

nom (text) : "string" 
description (text) : "string" 
photo (file) : Choose a file... 
prix (text) : "string" 
```

Succès 

```json
{
    "sucess": true,
    "message": "Repas créé avec succès"
}
``` 

Echec 

```json
{
    "success": false,
    "message": "Accès refusé, vous ne pouvez pas effectuer cette action."
}
```

<h4>Modifier un repas (gerant)</h4> 

```raw
PUT http://localhost/e-kaly/api/routes/repas/modifier.php?id_repas={number}
``` 

Headers 

```raw
Content-Type: application/json
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI...
```

Body 

```json  
{
    "nom": "string", 
    "description": "string", 
    "prix": number
}
``` 

Succès 

```json
{
    "success": true,
    "message": "Repas modifié avec succès."
}
``` 

Echec 

```json 
{
    "success": false, 
    "message": "Accès refusé, vous ne pouvez pas effectuer cette action."
}
``` 

<h4>Supprimer un repas (gerant)</h4> 

```raw
DELETE http://localhost/e-kaly/api/routes/repas/supprimer.php?id_repas={number}
```

Headers

```raw
Content-Type: application/json
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI...
```

Succès 

```json
{
    "success": true,
    "message": "Repas supprimé avec succès."
}
```

Echec 

```json
{
    "success": false, 
    "message": "Erreur de la suppression du repas."
}
``` 

<h2>Commande</h2> 

<h4>Création d'une commande (client)</h4> 

Header

```raw
Content-Type: application/json
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI...
```


```raw
POST http://localhost/e-kaly/api/routes/commande/creer.php
``` 

id_utilisateur récuperé depuis le token

Succès 

```json 
{
    "success": true,
    "message": "Commande créée avec succès."
}
``` 

Echec 

```json
{
    "success": false, 
    "message": "Erreur de la création du commande."
}
```