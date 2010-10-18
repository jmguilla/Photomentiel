package fr.photomentiel;

import java.io.File;

import fr.photomentiel.error.TraitementCommandeException;

public class Main {

	/**
	 * @param args
	 * @throws TraitementCommandeException 
	 */
	public static void main(String[] args) throws TraitementCommandeException {
		File[] reps = traiterParam(args);
		TraitementCommande tc = new TraitementCommande(reps[0]);
		tc.traiter(reps[1], reps[2]);
		System.out.println("Commande traitée avec succès");
	}

	/**
	 * Rend un tableau avec res[0] la commande sous format xml
	 * res[1] le repertoire source
	 * res[2] le repertoire dest
	 * @param args
	 * @return
	 * @throws TraitementCommandeException 
	 */
	public static File[] traiterParam(String[] args) throws TraitementCommandeException{
		String source = ".", dest = ".", commande = "commande.xml";
		File sourceDir = null, destDir = null, commandeFile = null;
		try{
			for(int i = 0; i < args.length; i++){
				if("-f".equals(args[i])){
					commande = args[i+1];
				}else if("-s".equals(args[i])){
					source = args[i+1];
				}else if("-d".equals(args[i])){
					dest = args[i+1];
				}
			}
			sourceDir = new File(source);
			destDir = new File(dest);
			commandeFile = new File(commande);
			if(!sourceDir.exists()){
				throw new TraitementCommandeException("Le r�pertoire source " + sourceDir.getAbsolutePath() + " n'existe pas");
			}
			if(!sourceDir.isDirectory()){
				throw new TraitementCommandeException("Le fichier " + sourceDir.getAbsolutePath() + " n'est pas un r�pertoire");
			}
			if(!destDir.exists()){
				creerRepertoire(destDir);
			}
			if(!commandeFile.exists()){
				throw new TraitementCommandeException("Fichier de commande " + commandeFile.getAbsolutePath() + " introuvable");
			}
		}catch(TraitementCommandeException e){
			throw e;
		}catch(Exception e){
			printHelp(e);
		}
		return new File[]{commandeFile, sourceDir, destDir};
	}

	private static void creerRepertoire(File destDir) throws TraitementCommandeException {
		String parent = destDir.getParent();
		File parentDir = new File(parent);
		if(!parentDir.exists()){
			creerRepertoire(parentDir);
		}if(!parentDir.isDirectory()){
			throw new TraitementCommandeException("Impossible de cr�er le r�pertoire de destination " + destDir.getAbsolutePath());
		}
		if(destDir.mkdir()){
			System.out.println("R�pertoire " + destDir.getAbsolutePath() + " cr��");
			return;
		}else{
			throw new TraitementCommandeException("Impossible de cr�er le r�pertoire de destination " + destDir.getAbsolutePath());
		}
	}

	private static void printHelp(Exception e) {
		if(e!=null){
			System.out.println(e.getMessage());
		}
		System.out.println(Main.class.getName() + " [-s r�pertoire source] [-d r�pertoire destination] [-f commande]");
		System.out.println("Par d�faut, source et dest valent ., commande vaut commande.xml");
		System.out.println("Produit un r�pertoire \"commande#numCommande\" dans le r�pertoire dest");
		System.out.println("depuis le fichier xml fourni avec les photos du r�pertoire source");
		System.out.println("Si le r�pertoire destination n'existe pas, il sera cr��.");
	}
}
