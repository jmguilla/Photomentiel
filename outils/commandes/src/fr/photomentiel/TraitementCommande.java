package fr.photomentiel;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.PrintWriter;

import fr.photomentiel.commande.Commande;
import fr.photomentiel.commande.LigneCommande;
import fr.photomentiel.error.TraitementCommandeException;
import fr.photomentiel.utils.ProcesseurXML;

public class TraitementCommande {
	private final Commande commande;
	public TraitementCommande(File commande) throws TraitementCommandeException{
		ProcesseurXML proc = new ProcesseurXML();
		this.commande = proc.buildCommande(commande);
	}

	public void traiter(File reps, File reps2) throws TraitementCommandeException{
		File destDir = new File(reps2.getAbsoluteFile() + File.separator + "commande#" + commande.numero);
		if(!destDir.exists()){
			if(!destDir.mkdir()){
				throw new TraitementCommandeException("Impossible de créer le répertoire de destination " + destDir.getAbsolutePath());
			}else{
				System.out.println("Création du répertoire de destination " + destDir.getAbsolutePath());
			}
		}
		for(LigneCommande ligne : commande.lignes){
			File source = new File(reps + File.separator + commande.homePhotographe + File.separator + ligne.photo);
			if(!source.exists()){
				throw new TraitementCommandeException("La photo " + source.getAbsolutePath() + " n'existe pas");
			}
			File dest = new File(destDir + File.separator + ligne.photo);
			try {
				copyFile(source, dest);
			} catch (FileNotFoundException e) {
				throw new TraitementCommandeException("Impossible de copier le fichier " + source.getAbsolutePath(), e);
			} catch (IOException e) {
				throw new TraitementCommandeException("Impossible de copier le fichier " + source.getAbsolutePath() + " vers " + dest.getAbsolutePath());
			}
		}
		copyAdresse(destDir);
	}

	private void copyAdresse(File dest) throws TraitementCommandeException {
		File recap = new File(dest.getAbsoluteFile() + File.separator + "index.html");
		PrintWriter pw = null;
		try {
			pw = new PrintWriter(recap);
			pw.println("<html><head><title>recapitulatif commande #" + commande.numero + "</title></head>");
			pw.println("<body>");
			pw.println("<h3>Destinataire<b>" + commande.addresse.prenom + " " + commande.addresse.nom + "</b></h3>");
			pw.println("adresse:");
			pw.println("<h3>" + commande.addresse.adresse1);
			if(commande.addresse.adresse2!=null && !commande.addresse.adresse2.equals("")){
				pw.println("<h3>" + commande.addresse.adresse2 + "</h3>");
			}
			pw.println("<h3>" + String.format("%05d",commande.addresse.codePostal) + " " + commande.addresse.ville);
		} catch (FileNotFoundException e) {
			throw new TraitementCommandeException("Impossible de générer le fichier de récap " + dest.getAbsoluteFile() + File.separator + "index.html", e);
		}finally{
			if(pw != null) {
				pw.close();
			}
		}
	}

	private void copyFile(File source, File dest) throws IOException {
		FileInputStream reader = new FileInputStream(source);
		FileOutputStream writer = new FileOutputStream(dest);
		System.out.println("Copie de " + source.getAbsolutePath() + " vers " + dest.getAbsolutePath());
		try{
			int c = -1;
			while(( c = reader.read()) != -1){
				writer.write(c);
			}
			System.out.println("Copie effectuée avec succés");
		}finally{
			writer.close();
			reader.close();
		}
	}
}
