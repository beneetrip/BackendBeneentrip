<?php

namespace BusinessModelBundle\Entity;


class PDFInvoice
{
				
				public function __construct()
    			{
    			}
    			
			  
			  
			  static function genererCodeCSS()
			  {		

				$css=<<<EOF
							<style>
							
							#TableEntete, #TableEnteteDroite, #TableCorps, #EnteteDroitCorps, #TablePied, #TablePiedGauche, #TablePiedDroit
							{
								width:100%;
								border-collapse: collapse;
							}
							
							#EnteteDroitCorps tr td
							{
							   border: 3px solid black;
							}
							
							td 
							{
								border: 3px solid black;
								text-align: center;
							}
							
							td.Droite 
							{
								border: 3px solid black;
								text-align: right;
							}
							
							td.Gauche 
							{
								border: 3px solid black;
								text-align: left;
							}
							
							td.Titre 
							{
								font-weight: bold;
							}
							
							tr#LigneCorps td{
							 	border: 3px solid black;
							}			
							</style>
EOF;
				
							return $css;
						}
						
			
			//On prend juste un paiement car la facture du paiement doit avoir une reference donc difficile de prendre plusieurs paiement			
			static function genererInvoiceCodeHTML(\BusinessModelBundle\Entity\User $user, \BusinessModelBundle\Entity\Payment $payment)
			
			{
				
						//On recupere la reservation du paiement afin d'extraire les differentes activites
						$reservation=$payment->getReservation();
							
						$nowDate=new \DateTime();
						
						$html='<!-- Entete -->
						<table id="TableEntete">
						<tr>
						<!-- Entete gauche -->
							<td style="border: 0px solid white;">
							
						<table id="TableEnteteGauche" style="width: 60%;">
						<tr>
							<td class="Titre" style="border: 0px solid white;">Invoice To <br/></td> 
						</tr>
						<tr>
							<td style="font-size: 25px;font-weight: bold; border: 0px solid white;" class="Gauche">Mr. '.$user->getNomComplet().'</td>
						</tr>
						<tr>
							<td style="border: 0px solid white;" class="Gauche">'.$user->getEmail().'</td>
						</tr>
						<tr>
							<td style="border: 0px solid white;" class="Gauche">'.$user->getTelephone().'</td>
						</tr>
						</table>	
							
							</td>
							
							<!-- Entete Droite -->
							<td style="border: 0px solid white;">
						
						<table id="TableEnteteDroite">
						<tr>
						<td colspan="2" style="border: 0px solid white; border-bottom: 0px solid black; font-size: 25px;font-weight: bold;">INVOICE</td>
						</tr>
						<tr style="background-color: #BFBFBF;">
						<td class="Titre">Tax Date</td>
						<td class="Titre">Invoice #</td>
						</tr>
						<tr>
						<td>'.$nowDate->format('d/m/Y').'</td>
						<td>'.$payment->getRef().'</td>
						</tr>
						</table>	
							
							</td>
						</tr>
						</table>
						
						<br/><br/><br/><br/>						<br/><br/><br/><br/>
						
						<!-- Table corps -->
						
						<table id="TableCorps">
						<tr>
							<td colspan="3" style="border: 0px solid white; border-right: 0px solid black;"></td>
							
							<td colspan="2" class="Titre" style="background-color: #BFBFBF;">Date</td>
						</tr>
						
						<tr>
							<td colspan="3" style="border: 0px solid white; border-bottom: 0px solid black; border-right: 0px solid black;"></td>
							
							<td colspan="2">'.$nowDate->format('d/m/Y').'</td>
						</tr>
						
						<tr style="background-color: #BFBFBF;">
							<td class="Titre" style="width: 35%;">Description</td>
							<td class="Titre" style="width: 5%;">Qty</td>
							<td class="Titre" style="width: 20%;">Rate</td>
							<td class="Titre" style="width: 20%;">VAT</td>
							<td class="Titre" style="width: 20%;">Amount</td>
						</tr>
						<!-- Ligne corps -->';
						foreach($reservation->getActivites() as $activite)	{
						$html.=' 
						<tr id="LigneCorps">
							<td class="Gauche">'.$activite->getLibelle().'</td>
							<td class="Droite">1</td>
							<td class="Droite">'.$activite->getPrixIndividu().'</td>
							<td class="Droite">'.$reservation->calculerMontantTaxeActivite($activite).' ('.$reservation->calculerTauxTaxeActivite($activite).' %)</td>
							<td class="Droite">'.$reservation->calculerMontantTotalActiviteAvecTaxe($activite).'</td>
						</tr>';
						}	
						$html.='<!-- Fin ligne Corps -->
						<tr>
							<td colspan="3" style="border: 0px solid white; border-right: 0px solid black;"></td>
							<td>Net Price</td>
							<td class="Droite">'.$reservation->calculerMontantTotal().' EUR</td>
						</tr>
						<tr>
							<td colspan="3" style="border: 0px solid white; border-right: 0px solid black;"></td>
							<td>VAT</td>
							<td class="Droite">'.$reservation->calculerMontantTaxeActiviteTotal().' EUR</td>
						</tr>
						<tr style="font-size: 20px;font-weight: bold;">
							<td colspan="3" style="border: 0px solid white; border-right: 0px solid black;"></td>
							<td>Total Invoice</td>
							<td class="Droite">'.$reservation->calculerMontantTotalAvecTaxe().' EUR</td>
						</tr>
						
						</table>
						
						<br/><br/><br/><br/>
						<div id="tete" style="text-align: center;color: #000;font-family: roboto,arial,sans-serif;font-size: 20px; font-weight: bold;border-top: 4px solid #FAFAFA;">
						Please direct all queries  to contact@beneentrip.com
						</div>';
						
						//return htmlspecialchars($html);//cette fonction nous htmlne le brut HTML en remplacant certains caracteres en code HTML: & devient &amp;
						return $html;	
						
			}	
						
		   //On prend ici plusieurs paiements et dans le cas ou on veut generer les factures etats pour un seul paiement former un array avec un seul paiement et le passer comme parametre			
			static function genererStatementCodeHTML(\BusinessModelBundle\Entity\User $user, $listPayments)
			{	
			
			
			$nowDate=new \DateTime();
			
			//Pour calculer la somme de credit de l'utilisateur
			$somme=0.0;
			
			$html='<!-- Entete -->
			<table id="TableEntete">
			<tr>
			<!-- Entete gauche -->
			<td style="border: 0px solid white;">
					
				<table id="TableEnteteGauche" style="width: 60%;">
				<tr>
					<td style="font-size: 25px;font-weight: bold; border: 0px solid white;" class="Gauche">Mr. '.$user->getNomComplet().'</td>
				</tr>
				<tr>
					<td style="border: 0px solid white;" class="Gauche">'.$user->getEmail().'</td>
				</tr>
				<tr>
					<td style="border: 0px solid white;" class="Gauche">'.$user->getTelephone().'</td>
				</tr>
				</table>	
							
							</td>
				
				<!-- Entete Droite -->
				<td style="border: 0px solid white;">
			
			<table id="TableEnteteDroite">
			<tr>
			<td colspan="2" style="border: 0px solid white; border-bottom: 0px solid black; font-weight: bold;text-align: left">Statement</td>
			</tr>
			<tr>
			<td class="Titre"  style="background-color: #BFBFBF;">Date</td>
			<td class="Titre">'.$nowDate->format('d/m/Y').'</td>
			</tr>
			<tr>
			<td class="Titre" style="background-color: #BFBFBF;">Account Ref</td>
			<td>'.$user->getId().''.$nowDate->format('mY').'</td>
			</tr>
			</table>	
				
				</td>
			</tr>
			</table>
			
			<br/><br/><br/><br/>
			
			<!-- Table corps -->
			
			<table id="TableCorps">
			
			<tr style="background-color: #BFBFBF;">
				<td class="Titre">Date</td>
				<td class="Titre">Ref</td>
				<td class="Titre">Details</td>
				<td class="Titre">Debit</td>
				<td class="Titre">Credit</td>
				<td class="Titre">Balance</td>
			</tr>
			<!-- Ligne corps -->';
			foreach($listPayments as $payment)	{
			$reservation=$payment->getReservation();
			$listActivites=$reservation->getListActivitesUtilisateur($user);
			
			foreach($listActivites as $activite)	{
			$html.='
			<tr id="LigneCorps">
				<td>'.date_format($activite->getDate(),'d/m/Y').'</td>
				<td>'.$payment->getRef().'</td>
				<td>'.$activite->getLibelle().'</td>
				<td>0 EUR</td>
				<td>'.$activite->getPrixIndividu().' EUR</td>
				<td>'.$activite->getPrixIndividu().' EUR</td>
			</tr>';
			$somme+=floatval($activite->getPrixIndividu());	
			}

			}
			
			$html.='<!-- Fin ligne Corps -->
			
			</table>
			
			<br/><br/><br/><br/>
			<div id="tete" style="text-align: center;color: #000;font-family: roboto,arial,sans-serif;font-size: 20px; font-weight: bold;border-top: 2px solid #FAFAFA;">
			</div>
			
			<br/><br/><br/><br/>
			
			<!-- Pied -->
			<table id="TablePied">
			<tr>
			<!-- Pied gauche -->
				<td style="border: 0px solid white; width: 60%;">
				
			
				
				</td>
				
				<!-- Pied Droit -->
				<td style="border: 0px solid black; width: 40%;">
			
			<table id="TablePiedDroit">
			<tr style="background-color: #BFBFBF;">
			<td class="Titre">Total Debit</td>
			<td class="Titre">Total Credit</td>
			<td class="Titre">Total Balance</td>
			</tr>
			<tr>
			<td>0 EUR</td>
			<td>'.$somme.' EUR</td>
			<td>'.$somme.' EUR</td>
			</tr>
			</table>	
				
				</td>
			</tr>
			</table>
			
			<br/><br/><br/><br/>
			
			<br/><br/><br/><br/>
			<div id="tete" style="text-align: center;color: #000;font-family: roboto,arial,sans-serif;font-size: 20px; font-weight: bold;border-top: 4px solid #FAFAFA;">
			Please direct all queries  to contact@beneentrip.com
			</div>';
			//return htmlspecialchars($html);//cette fonction nous htmlne le brut HTML en remplacant certains caracteres en code HTML: & devient &amp;
			return $html;
			
		   }		
						
						
			//fonction permettant de creer le PDF a partir des templates HTML et CSS
			//typeInvoice true: Invoice, false: Statement
			static function genererPDFWithTCPDF($codeHTML,$codeCSS,$typeInvoice)
			{
						    
						 require_once(__DIR__.'/../../../src/AdminBundle/Resources/public/tcpdf/tcpdf.php');
					 	 
					 	 $lang='en';
					    
					    // create new PDF document
					    $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
					 
					    // set document information
					    $pdf->SetCreator(PDF_CREATOR);
					    $pdf->SetAuthor('Beneen Trip');
					    $pdf->SetTitle('Beneen Trip');
					    $pdf->SetSubject('Beneen Trip');
					    //$pdf->SetKeywords('TCPDF, PDF, tab, appphp, print');
					 
					    // set default header data
					   $logo='../../../../../../AdminBundle/Resources/public/img/logoBeneenTrip.png';
					   $logowidth=15;
					   $title="Beneen Trip";
					   //On recupere la date en cours
					   $nowDate=new \DateTime();
					   $string="Date : ".$nowDate->format('d/m/Y H:i:s');
					   //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
						$pdf->SetHeaderData($logo, $logowidth,$title, $string);
					    // set header and footer fonts
					    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
					    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
					 
					    // set default monospaced font
					    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
					 
					    //set margins
					    //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
					    $pdf->SetMargins(10, PDF_MARGIN_TOP, 10);
					    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
					    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
					 
					    //set auto page breaks
					    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
					 
					    //set image scale factor
					    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
					 
					    //set some language-dependent strings
					    $pdf->setLanguageArray($lang);
					 
					    // set font
					    $pdf->SetFont('helvetica', '', 10);
					 
					    // add a page
					    $pdf->AddPage();
					
						//$codefinal=htmlspecialchars($codeCSS.''.$codeHTML);
					    
					    $pdf->writeHTML($codeCSS.''.$codeHTML, true, false, true, false, '');
					    
					    // reset pointer to the last page
					    $pdf->lastPage();
					    
					    //if (ob_get_length()) ob_end_clean();
					    
					    if(ob_get_contents())
					     ob_end_clean(); //add this line here to avoid TCPDF ERROR: Some data has already been output, can't send PDF file
					    
					    //Close, output PDF document
					    //$pdf->Output('monDocPrint.pdf', 'I');
					    //Close, save PDF document on disk
					    $nowDate=new \DateTime();	
					    
					    $annee = $nowDate->format('Y');
						 $mois = $nowDate->format('m');
						 $jour = $nowDate->format('d');
						 $heure = $nowDate->format('H');
						 
						 //TypeInvoice determine le type de PDF qu'on veut produire
						 $dossierType="";
						 if($typeInvoice)
						 $dossierType="invoices";
						 else
						 $dossierType="statements";
						 
					    $dir=__DIR__.'/../../../web/'.$dossierType.'/'.$annee.'/'.$mois.'/'.$jour.'/'.$heure;
					    
					    if (!file_exists($dir)) {
					    mkdir($dir, 0777, true);
					    chmod($dossierType.'/'. $annee, 0777);
						 chmod($dossierType.'/'. $annee .'/'. $mois, 0777);
						 chmod($dossierType.'/'. $annee .'/'. $mois .'/'. $jour, 0777);
						 chmod($dossierType.'/'. $annee .'/'. $mois .'/'. $jour .'/'. $heure, 0777);
						 }
							
						 $file=$dir.'/'.$nowDate->format('YmdHis').'.pdf';
					    $pdf->Output($file, 'F');
						 
						 //on retourne le chemin relatif
						 return $dossierType.'/'.$nowDate->format('Y/m/d/H').'/'.$nowDate->format('YmdHis').'.pdf';
					}
					
}
