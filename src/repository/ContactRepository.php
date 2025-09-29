<?php
require_once __DIR__ . '/../modele/Contact.php';
require_once __DIR__ . '/../bdd/Bdd.php';

class ContactRepository
{
    private $bdd;

    public function __construct()
    {
        $this->bdd = new Bdd();
    }

    public function creerContact(Contact $contact)
    {
        $req = $this->bdd->getBdd()->prepare('
            INSERT INTO contacts (nom, email, sujet, message)
            VALUES (:nom, :email, :sujet, :message)
        ');

        return $req->execute([
            'nom'     => $contact->getNom(),
            'email'   => $contact->getEmail(),
            'sujet'   => $contact->getSujet(),
            'message' => $contact->getMessage()
        ]);
    }

    public function getTousLesContacts()
    {
        $req = $this->bdd->getBdd()->query('SELECT * FROM contacts');
        $data = $req->fetchAll(PDO::FETCH_ASSOC);

        $contacts = [];
        foreach ($data as $row) {
            $contacts[] = new Contact($row);
        }
        return $contacts;
    }
}
