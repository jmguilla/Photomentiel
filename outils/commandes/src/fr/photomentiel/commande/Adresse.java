package fr.photomentiel.commande;

public class Adresse {
	public final String prenom, nom, adresse1, adresse2, ville;
	public final int codePostal;
	public Adresse(String prenom, String nom, String ad1, String ad2, int cp, String ville){
		this.prenom = prenom;
		this.nom = nom;
		this.adresse1 = ad1;
		this.adresse2 = ad2;
		this.codePostal = cp;
		this.ville = ville;
	}
}
