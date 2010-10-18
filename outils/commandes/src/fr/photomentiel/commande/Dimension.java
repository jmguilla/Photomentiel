package fr.photomentiel.commande;

public enum Dimension {
	_10x13("Standard", "10x13" ),_10x15("Standard","10x15" ),_13x17("Moyen","13x17"),
	_13x19("Moyen","13x19"),_15x20("Grand","15x20"),_15x21("Grand","15x21"),
	_20x27("Agrandissement","20x27"),_20x30("Agrandissement","20x30");
	public final String taille;
	public final String desc;
	Dimension(String desc, String taille){
		this.desc = desc;
		this.taille = taille;
	}
	public static Dimension getDimension(String dim){
		String dimString = dim.toLowerCase();
		for(Dimension dimension : Dimension.values()){
			if(dimension.taille.equals(dimString)){
				return dimension;
			}
		}
		return null;
	}
	@Override
	public String toString(){
		return this.taille;
	}
}
