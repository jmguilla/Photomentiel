package fr.photomentiel.commande;

import java.util.ArrayList;

public class Commande {
	public final ArrayList<LigneCommande> lignes;
	public final Adresse addresse;
	public final String homePhotographe;
	public final String numero;
	public Commande(ArrayList<LigneCommande> lignes, Adresse addresse, String numero, String hp){
		this.lignes = lignes;
		this.addresse = addresse;
		this.numero = numero;
		this.homePhotographe = hp;
	}
}
