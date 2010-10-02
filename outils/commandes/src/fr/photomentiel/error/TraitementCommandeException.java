package fr.photomentiel.error;


public class TraitementCommandeException extends Exception{

	public TraitementCommandeException(String string, Throwable e) {
		super(string, e);
	}

	public TraitementCommandeException(String string) {
		super(string);
	}

}
