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