package fr.photomentiel.commande;


public class LigneCommande {
	public final String photo;
	public final Dimension dimension;
	public final int nombre;
	public LigneCommande(String photo, Dimension dim, int nb){
		this.photo = photo;
		this.dimension = dim;
		this.nombre = nb;
	}
}
