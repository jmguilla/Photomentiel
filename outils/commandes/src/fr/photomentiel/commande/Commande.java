package fr.photomentiel.commande;

import java.util.ArrayList;

public class Commande {
	public final ArrayList<LigneCommande> lignes;
	public final Adresse addresse;
	public final String homePhotographe;
	public final String numero;
	public final String stringID;
	public Commande(ArrayList<LigneCommande> lignes, Adresse addresse, String numero, String hp, String sid){
		this.lignes = lignes;
		this.addresse = addresse;
		this.numero = numero;
		this.homePhotographe = hp;
		this.stringID = sid;
	}
}
