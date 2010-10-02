package fr.photomentiel.commande;

import java.util.ArrayList;

public class Commande {
	public final ArrayList<LigneCommande> lignes;
	public final Adresse addresse;
	public final String numero;
	public Commande(ArrayList<LigneCommande> lignes, Adresse addresse, String numero){
		this.lignes = lignes;
		this.addresse = addresse;
		this.numero = numero;
	}
}
