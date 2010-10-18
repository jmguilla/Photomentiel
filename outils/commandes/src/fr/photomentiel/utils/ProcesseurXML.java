package fr.photomentiel.utils;

import java.io.File;
import java.io.IOException;
import java.util.ArrayList;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.ParserConfigurationException;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.NodeList;
import org.xml.sax.SAXException;

import fr.photomentiel.commande.Adresse;
import fr.photomentiel.commande.Commande;
import fr.photomentiel.commande.Dimension;
import fr.photomentiel.commande.LigneCommande;
import fr.photomentiel.error.TraitementCommandeException;

public class ProcesseurXML {
	public final Commande buildCommande(File commande) throws TraitementCommandeException{
		DocumentBuilder db;
		try {
			db = DocumentBuilderFactory.newInstance().newDocumentBuilder();
			Document document = db.parse(commande);
			Adresse adresse = this.buildAdresse(document);
			ArrayList<LigneCommande> lignes = this.buildLignes(document);
			String numero = this.getNumero(document);
			String hp = this.getHomePhotographe(document);
			String sid = this.getStringID(document);
			return new Commande(lignes, adresse, numero, hp, sid);
		} catch (ParserConfigurationException e) {
			throw new TraitementCommandeException("Aucun processeur xml trouvé", e);
		} catch (SAXException e) {
			throw new TraitementCommandeException("XML Invalide", e);
		} catch (IOException e) {
			throw new TraitementCommandeException("Fichier " + commande.getAbsolutePath() + " non trouvé", e);
		}
	}

	private String getStringID(Document document) {
		Element root = document.getDocumentElement();
		return root.getAttribute("stringID");
	}

	private String getHomePhotographe(Document document) {
		Element root = document.getDocumentElement();
		return root.getAttribute("homePhotographe");
	}

	private String getNumero(Document document) {
		Element root = document.getDocumentElement();
		return root.getAttribute("numero");
	}

	private ArrayList<LigneCommande> buildLignes(Document document) throws TraitementCommandeException {
		try{
			Element root = document.getDocumentElement();
			NodeList photos = root.getElementsByTagName("photo");
			ArrayList<LigneCommande> result = new ArrayList<LigneCommande>();
			for(int i = 0; i < photos.getLength(); i++){
				Element photo = (Element) photos.item(i);
				String nomPhoto = photo.getTextContent();
				Dimension dimension = Dimension.getDimension(photo.getAttribute("dimensions"));
				if(dimension == null){
					throw new TraitementCommandeException("Dimension photo inconnue: " + photo.getAttribute("dimensions"));
				}
				String qttString = photo.getAttribute("quantite");
				int qtt = Integer.parseInt(qttString);
				result.add(new LigneCommande(nomPhoto, dimension, qtt));
			}
			return result;
		}catch(Exception e){
			throw new TraitementCommandeException("Impossible de cr�er la commande", e);
		}
	}

	private Adresse buildAdresse(Document document) throws TraitementCommandeException {
		try{
			Element root = document.getDocumentElement();
			NodeList adresses = root.getElementsByTagName("adresse");
			for(int i = 0; i < adresses.getLength(); i++){
				Element adresse = (Element) adresses.item(i);
				if("adresse".equals(adresse.getNodeName())){
					NodeList childs = adresse.getChildNodes();
					String prenom = null, nom = null, adresse1 = null, adresse2 = null, ville = null;
					int cp = -1;
					for(int j = 0; j < childs.getLength(); j++){
						try{
							Element tmp = (Element) childs.item(j);
							if("prenom".equals(tmp.getNodeName())){
								prenom = tmp.getTextContent();
							}else if("nom".equals(tmp.getNodeName())){
								nom = tmp.getTextContent();
							}else if("adresse1".equals(tmp.getNodeName())){
								adresse1 = tmp.getTextContent();
							}else if("adresse2".equals(tmp.getNodeName())){
								adresse2 = tmp.getTextContent();
							}else if("codePostal".equals(tmp.getNodeName())){
								String cpString = tmp.getTextContent();
								cp = Integer.parseInt(cpString, 10);
							}else if("ville".equals(tmp.getNodeName())){
								ville = tmp.getTextContent();
							}else{
								System.err.println("Attribut d'adresse inconnue: " + tmp.getNodeName());
							}
						}catch(ClassCastException cce){
							continue;
						}
					}
					if(prenom == null || nom == null || adresse1 == null || ville == null || cp < 0){
						throw new TraitementCommandeException("Impossible de cr�er l'adresse. Informations manquantes");
					}else{
						return new Adresse(prenom, nom, adresse1, adresse2, cp, ville);
					}
				}
			}
		}catch(TraitementCommandeException e){
			throw e;
		}catch(Exception e){
			throw new TraitementCommandeException("Impossible de cr�er la commande", e);
		}
		throw new TraitementCommandeException("Aucune adresse trouv�e");
	}
}
