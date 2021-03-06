package fr.photomentiel;

import java.io.BufferedInputStream;
import java.io.BufferedOutputStream;
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
			File source = new File(reps + File.separator + commande.homePhotographe + File.separator + commande.stringID + File.separator + ligne.photo);
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
			pw.println("<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/><title>recapitulatif commande #" + commande.numero + "</title></head>");
			pw.println("<body><div>");
			pw.println("Destinataire: <input type=\"text\" value=\"" + commande.addresse.prenom + "\" readonly/>&nbsp;&nbsp;<input type=\"text\" value=\"" + commande.addresse.nom + "\" readonly/></br>");
			pw.println("adresse: <input type=\"text\" value=\"" + commande.addresse.adresse1 + "\" readonly /></br>");
			if(commande.addresse.adresse2!=null && !commande.addresse.adresse2.equals("")){
				pw.println("<input type=\"text\" value=\"" + commande.addresse.adresse2 + "\" readonly /></br>");
			}
			pw.println("<input type=\"text\" value=\"" + String.format("%05d",commande.addresse.codePostal) + "\" readonly /><input type=\"text\" value=\"" + commande.addresse.ville + "\" readonly /></br>");
			for(LigneCommande lc : commande.lignes){
				pw.println("<h4>" + lc.photo + " - " + lc.dimension + "<img height=\"10%\" width=\"10%\" src=\"" + lc.photo + "\"/></h4>");
			}
			pw.println("</body></html>");
		} catch (FileNotFoundException e) {
			throw new TraitementCommandeException("Impossible de générer le fichier de récap " + dest.getAbsoluteFile() + File.separator + "index.html", e);
		}finally{
			if(pw != null) {
				pw.close();
			}
		}
	}

	private void copyFile(File source, File dest) throws IOException {
		final int bufferSize = 1024 * 1000;
		BufferedInputStream reader = new BufferedInputStream(new FileInputStream(source), bufferSize);
		BufferedOutputStream writer = new BufferedOutputStream(new FileOutputStream(dest), bufferSize);
		byte[] buffer = new byte[bufferSize];
		System.out.println("Copie de " + source.getAbsolutePath() + " vers " + dest.getAbsolutePath());
		try{
			int c = -1;
			while(( c = reader.read(buffer)) != -1){
				writer.write(buffer, 0, c);
			}
			System.out.println("Copie effectuée avec succés");
		}finally{
			writer.close();
			reader.close();
		}
	}
}
