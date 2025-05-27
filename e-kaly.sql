CREATE TABLE Utilisateur (
    id_utilisateur NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    nom VARCHAR2(50) NOT NULL,
    prenom VARCHAR2(50) NOT NULL,
    email VARCHAR2(100) UNIQUE NOT NULL,
    mot_passe VARCHAR2(100) NOT NULL,
    role VARCHAR2(20) CHECK (role IN ('agent', 'gerant')) NOT NULL
);

CREATE TABLE Repas (
    id_repas NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    id_utilisateur NUMBER NOT NULL,
    nom VARCHAR2(100) NOT NULL,
    description VARCHAR2(500),
    photo VARCHAR2(100),
    prix NUMBER(10,2) NOT NULL CHECK (prix > 0),
    FOREIGN KEY (id_utilisateur) REFERENCES Utilisateur(id_utilisateur)
);

CREATE TABLE Agent (
    id_utilisateur NUMBER PRIMARY KEY,
    service VARCHAR2(50) NOT NULL,
    FOREIGN KEY (id_utilisateur) REFERENCES Utilisateur(id_utilisateur)
);

CREATE TABLE Gerant (
    id_utilisateur NUMBER PRIMARY KEY,
    date_nomination DATE DEFAULT SYSDATE NOT NULL,
    FOREIGN KEY (id_utilisateur) REFERENCES Utilisateur(id_utilisateur)
);

CREATE TABLE Commande (
    id_commande NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    date_commande DATE DEFAULT SYSDATE NOT NULL, 
    id_utilisateur NUMBER NOT NULL,
    FOREIGN KEY (id_utilisateur) REFERENCES Utilisateur(id_utilisateur)
);

-- Table d'association (Commande ---1..n-----------avoir-----------1..n--- Repas )
CREATE TABLE CommandeRepas (
    id_commande_repas NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY, 
    id_repas NUMBER NOT NULL, 
    id_commande NUMBER NOT NULL, 
    quantite NUMBER NOT NULL CHECK (quantite > 0),
    FOREIGN KEY (id_repas) REFERENCES Repas(id_repas), 
    FOREIGN KEY (id_commande) REFERENCES Commande(id_commande)
); 