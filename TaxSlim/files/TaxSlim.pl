#!/usr/bin/perl
use strict;
use warnings;
use LWP::Simple;
use XML::Simple;
use Data::Dumper;
use Scalar::Util qw(looks_like_number);
use List::Util qw( min max );
use POSIX qw(ceil);
use Getopt::Std;
use CGI;
use CGI::Cookie;

my $Parser = new XML::Simple;
my %options=();
my %Duplicates=();
my %TaxSlim=();
my $Amount;
my $Input;
my $cgi = CGI->new;
my $Depth;
my $Duplicate;
my $NameTS;
my $NameHits;
my $NameLog;
	$Depth=$cgi->param('depth'); #Assigns Depth from server
	$Duplicate=$cgi->param('Dup'); #Assigns if user wanted duplicates from server
	my $Type=$cgi->param('MyDropdown'); #If the user chose all or a specific number of GIs

	##Assigns a number of GIs to scan based on what user selected
	if ($Type eq "all"||$Type eq ""){$Amount ="all";}
	 else{$Amount=$cgi->param('Size'); $Amount="all" if($Amount eq"");}	
	my $upload_filehandle=$cgi->upload('Input');
	my $upload_dir="/Program Files (x86)/Apache Software Foundation/Apache2.2/htdocs/upload";
	$Input=$upload_filehandle if(defined $upload_filehandle);
	my @In=$cgi->param('textcontent');
	
my $Track=0;
my $GICounter=0;
my %RedundantDuplicates=();
my $Server="/Program Files (x86)/Apache Software Foundation/Apache2.2/htdocs/files"; #Server URL to send files
  my $random_number = rand(); #Unique identifier for each query

#Calling Revised Input to scan for GIs
&RevisedInput();
#Printing Tax Slim to file
&Print();
# TaxSlim Name cookie to be sent to server
my $cookie1 = $cgi->cookie( {-name => 'Taxfile', -value => $NameTS, -expires=>'+10m',
          -path=>'/'});
# Hits Name cookie to be sent to server
my $cookie2 = $cgi->cookie( {-name => 'Hitsfile', -value => $NameHits, -expires=>'+10m',
          -path=>'/'});
# Log Name cookie to be sent to server
my $cookie3 = $cgi->cookie( {-name => 'Logfile', -value => $NameLog, -expires=>'+10m',
          -path=>'/'});

#Sending cookies to sever
print  $cgi->header( -cookie => [$cookie1,$cookie2,$cookie3]);
print $cgi->start_html('My cookie-set.cgi program');
print $cgi->end_html;


close TS; #Tax Slim File
close HI; #Hit Amount File 
close LO; #Log File
print <<WEB_PAGE;
    <html>
    </html>
WEB_PAGE
#print header( {-cookie => [$firstcookie]} );
############################################################################################################
sub RevisedInput{

#Directory and Name of TaxSlim file
my $TaxSlim=$Server."\/"."TaxSlim".$Depth."D_".$Amount."_".$random_number.".csv";
$NameTS="TaxSlim".$Depth."D_".$Amount."_".$random_number.".csv";
open(TS,">", $TaxSlim);

#Directory and Name of Hits file
my $Hits=$Server."\/"."Hits".$Depth."D_".$Amount."_".$random_number.".csv";
$NameHits="Hits".$Depth."D_".$Amount."_".$random_number.".csv";

#Directory and Name of Log file
my $Log=$Server."\/"."Log".$Depth."D_".$Amount."_".$random_number.".csv";
$NameLog="Log".$Depth."D_".$Amount."_".$random_number.".csv";

print TS "GI,Scientific Name,Lineage,TaxID\n";


open(HI,">",$Hits);
open(LO,">",$Log);

#If User Chose all Gis
if ($Amount eq "all") {
    if ($upload_filehandle eq undef) { #If user used Text Area not file
        while(<@In>){
	    &ScanAll($_);
	}
    }
#If user chose a specific number of GIs  
    else{
	 while(<$Input>){ #If user used file not text area
	    &ScanAll($_);
	}
    }
    

}
else{
#If User chose a specific amonunt of GIs    
    $GICounter=$Amount;
    my $x=0;
    while(<$Input>){
    $_ =~ s/\R//g;	   
	&GetTaxIds($_);
       if ($Duplicate=~ /^[Y]?$/i||!exists ($Duplicates{$_})&&$Duplicate=~ /^[N]?$/i)
       { $x++;}
       $Duplicates{$_}++;
        if($x eq $Amount){last;} 
    }
    
}
}
############################################################################################################
#Sub routine to scanll all GIs
sub ScanAll{
my $GI=$_;    
$GI =~ s/\R//g;	   
	$Duplicates{$GI}++;
        $GICounter++;
        &GetTaxIds($GI);
      }    

##########################################################################################
sub GetTaxIds{
      my $TaxId="N/A";
      my $GI=$_[0];
 #URL to Eutils to get tax IDs from GIs
 my $Url="http://eutils.ncbi.nlm.nih.gov/entrez/eutils/elink.fcgi?dbfrom=nucleotide&db=taxonomy&id=".$GI; 
      my $Result = get ($Url);
      my $Data = $Parser->XMLin($Result);
      #If GI has more than one Tax ID
        if (ref($Data->{LinkSet}{LinkSetDb}{Link}) eq 'ARRAY'){
        $TaxId="";
         for (@{ $Data->{LinkSet}{LinkSetDb}{Link} }) {
          &GetFullLineage($GI, $_->{Id},$Depth);
          }
        }
        #If GI contains only one Tax ID
	elsif (ref($Data->{LinkSet}{LinkSetDb}{Link}) ne 'ARRAY'){
             if(exists($Data->{LinkSet}{LinkSetDb}{Link}{Id})) {
               $TaxId=$Data->{LinkSet}{LinkSetDb}{Link}{Id};
               &GetFullLineage($GI, $TaxId,$Depth);
            }
             #For Dead GIs that contain no Tax IDs
	     else{$TaxSlim{"UnSpecified GIs"}{$GI}++;
                  $RedundantDuplicates{"UnSpecified GIs"}++ if($TaxSlim{"UnSpecified GIs"}{$GI}>1);
                  }

        }

}
#END OF GET TAXIDs
#########################################################################################

#########################################################################################
sub GetFullLineage{
#my $Info = $_[0];
my $GI= $_[0];
my $TaxID = $_[1];
my $Depth= $_[2];
my $Lineage="";
	#Eutils to get lineage from tax ID
	my $Url="http://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=taxonomy&rettype=xml&retmode=text&id=".$TaxID;
        my $Result = get ($Url);
        my $Data = $Parser->XMLin($Result);
        if (ref($Data->{Taxon}{LineageEx}{Taxon}) eq 'ARRAY'){
            my @Levels=@{ $Data->{Taxon}{LineageEx}{Taxon}};
            if ($Depth>@Levels){
                $Depth=@Levels-1;
            }
	    #Making lineage up to certain depth: Name/TaxID
            for (my $x = 0; $x <$Depth; $x++) {          
              $Lineage=$Lineage."/".$Levels[$x]->{ScientificName}."(".$Levels[$x]->{TaxId}.")" if ($Lineage ne "");
              $Lineage=$Levels[$x]->{ScientificName}."(".$Levels[$x]->{TaxId}.")"  if ($Lineage eq "");
              
              }
            #$Info=$Info.";".$Data->{Taxon}{ScientificName}.";".$Data->{Taxon}{TaxId};
            if($Duplicate=~ /^[Y]?$/i){
              $TaxSlim{$Lineage}{$GI}++;
               $RedundantDuplicates{$Lineage}++ if($TaxSlim{$Lineage}{$GI}>1);
              }
            else{$TaxSlim{$Lineage}{$GI}=1;  
            }
            print TS "\n$GI,$Data->{Taxon}{ScientificName},$Lineage,$Data->{Taxon}{TaxId}\n" if($Duplicate=~ /^[Y]?$/i||!exists ($Duplicates{$_})&&$Duplicate=~ /^[N]?$/i);
            #Prints GI, Lineage, Scientific Name, TaxID
        }
}

#END OF GetFullLineage
########################################################################################

########################################################################################
#Determines the percantage of hits for a given lineage
sub GetPercentage{
my $Lineage=$_[0];
my $Percent=keys %{$TaxSlim{$Lineage}};
my $Size=0;
 #Includes duplicates if "y" is selected
 $Size=$RedundantDuplicates{$Lineage} if($Duplicate=~ /^[Y]?$/i&& exists($RedundantDuplicates{$Lineage})) ;
#Calculating %
$Percent=($Percent+$Size)/$GICounter*100;
return $Percent;
}

########################################################################################

########################################################################################
sub Print{
        my $Lineage="";
        my %Final=();
         my $Percentage=0;
    foreach my $ThisLineage (keys %TaxSlim){
            $Lineage="\n$ThisLineage";
            $Percentage=&GetPercentage($ThisLineage);
               	my $GIs="";
		foreach my $ThisGI (keys %{$TaxSlim{$ThisLineage}}){
                 $GIs= "$GIs\/$ThisGI";
		      if($TaxSlim{$ThisLineage}{$ThisGI}>1){
			 my $size=$TaxSlim{$ThisLineage}{$ThisGI};
			 for(my $x=1;$x<$size;$x++){
			    $GIs= "$GIs\/$ThisGI";
			}
		      }
            }
                my $str1 = substr($GIs, 1);
	    $Final{$Percentage}{$ThisLineage}=$str1;
    }
print HI "Lineage,Hits,Percentage,GIs\n";
foreach my $P (sort {$b <=> $a} keys %Final){
    foreach my $Lineage (keys $Final{$P}){
            my $Others=0;
	    $Others=$RedundantDuplicates{$Lineage} if($Duplicate=~ /^[Y]?$/i&& exists($RedundantDuplicates{$Lineage})) ;
	    my $Hits=keys($TaxSlim{$Lineage})+$Others;
	    print HI "\n$Lineage,$Hits,$P,$Final{$P}{$Lineage}\n";
    }

}
  
    print LO "Were duplicate GIs counted toward percentage? ". ($Duplicate)."\n";
      print LO "Duplicate GIs:\n";
    print LO "GI,Occurance\n";
    while( my ($k, $v) = each %Duplicates ) {
        print LO "$k,$v\n"if($v>1);
    }
  
}
  