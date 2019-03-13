/** @license
 * Copyright (c) 2013-2016 Ephox Corp. All rights reserved.
 * This software is provided "AS IS," without a warranty of any kind.
 */
!function(){var a=function(){return{a11y:{widget:{title:"Tillg\xe4nglighetskontroll",running:"Kontrollerar...",issue:{counter:"Problem {0} av {1}",help:"WCAG 2.0-referens \u2013 \xf6ppnas i ett nytt f\xf6nster",none:"Inga tillg\xe4nglighetsproblem hittades"},previous:"F\xf6reg\xe5ende problem",next:"N\xe4sta problem",repair:"Reparera problem",available:"Reparation tillg\xe4nglig",ignore:"Ignorera"},image:{alttext:{empty:"Bilder m\xe5ste ha en alternativ beskrivande text",filenameduplicate:"Den alternativa texten f\xe5r inte vara densamma som filnamnet f\xf6r bilden",set:"Ange alternativ text:",validation:{empty:"Den alternativa texten f\xe5r inte vara tom",filenameduplicate:"Den alternativa texten f\xe5r inte vara densamma som filnamnet"}}},table:{caption:{empty:"Tabeller m\xe5ste ha en bildtext",summaryduplicate:"Tabellens bildtext och sammanfattning kan inte ha samma v\xe4rde",set:"Ange bildtext:",validation:{empty:"Bildtexten f\xe5r inte vara tom",summaryduplicate:"Tabellbeskrivningstexten f\xe5r inte vara samma som tabellens sammanfattning"}},summary:{empty:"Invecklade tabeller ska ha en sammanfattning",set:"Ange sammanfattning f\xf6r tabellen.",validation:{empty:"Sammanfattningen f\xe5r inte vara tom",captionduplicate:"Tabellsammanfattningen f\xe5r inte vara samma som tabellens beskrivning"}},rowscells:{none:"Tabellelement m\xe5ste inneh\xe5lla TR- och TD-taggar"},headers:{none:"Tabeller m\xe5ste ha minst en rubrikcell",set:"V\xe4lj tabellrubrik:",validation:{none:"V\xe4lj antingen rad- eller kolumnrubrik"}},headerscope:{none:"Tabellrubriker m\xe5ste till\xe4mpas p\xe5 en rad eller en kolumn",set:"V\xe4lj rubrikomfattning:",scope:{row:"Rad",col:"Kolumn",rowgroup:"Radgrupp",colgroup:"Kolumngrupp"}}},heading:{nonsequential:"Rubriker m\xe5ste till\xe4mpas i ordningsf\xf6ljd. Till exempel: Rubrik 1 ska f\xf6ljas av Rubrik 2 och inte av Rubrik 3.",paragraphmisuse:"Detta stycke verkar vara en rubrik. Om det \xe4r en rubrik ska du v\xe4lja en rubrikniv\xe5.",set:"V\xe4lj en rubrikniv\xe5:"},link:{adjacent:"Intilliggande l\xe4nkar som leder till samma URL ska sl\xe5s samman till en enda l\xe4nk"},list:{paragraphmisuse:"Den markerade texten verkar vara en lista. Listor ska formateras med hj\xe4lp av en listtagg."},contrast:{smalltext:"Text m\xe5ste ha ett kontrastf\xf6rh\xe5llande p\xe5 minst 4,5:1",largetext:"Stor text m\xe5ste ha ett kontrastf\xf6rh\xe5llande p\xe5 minst 3:1"},severity:{error:"Fel",warning:"Varning",info:"Informativt"}},aria:{autocorrect:{announce:"Autokorrigering {0}"},label:{toolbar:"Verktygsf\xe4lt f\xf6r redigerare av Rik text",editor:"Redigerare av Rik text, Textbox.io \u2013 {0}",fullscreen:"Redigerare av Rik text i helsk\xe4rm Textbox.io \u2013 {0}",content:"Redigerbart inneh\xe5ll",more:"Klicka f\xf6r att utvidga eller d\xf6lja"},help:{mac:"Tryck p\xe5 \u2303\u2325H f\xf6r hj\xe4lp",ctrl:"Tryck p\xe5 CTRL SHIFT H f\xf6r hj\xe4lp"},color:{picker:"F\xe4rgv\xe4ljare",menu:"F\xe4rgv\xe4ljarmeny"},font:{color:"Textf\xe4rger",highlight:"Markeringsf\xe4rger",palette:"F\xe4rgpalett"},context:{menu:{generic:"Sammanhangsmeny"}},stepper:{input:{invalid:"Storleksv\xe4rde ogiltigt"}},table:{headerdescription:"Tryck p\xe5 mellanslag f\xf6r att aktivera inst\xe4llningen. Tryck p\xe5 tabtangenten f\xf6r att \xe5terg\xe5 till tabellv\xe4ljaren.",cell:{border:{size:"Kanttjocklek"}}},input:{invalid:"Ogiltig inmatning"},widget:{navigation:"Anv\xe4nd piltangenterna f\xf6r att navigera."},image:{crop:{size:"Besk\xe4rningsstorleken \xe4r {0} pixlar per {1} pixlar"}}},color:{white:"Vit",black:"Svart",gray:"Gr\xe5",metal:"Metall",smoke:"R\xf6k",red:"R\xf6d",darkred:"M\xf6rkr\xf6d",darkorange:"M\xf6rkorange",orange:"Orange",yellow:"Gul",green:"Gr\xf6n",darkgreen:"M\xf6rkgr\xf6n",mediumseagreen:"Mellangr\xf6n (sj\xf6gr\xf6n)",lightgreen:"Ljusgr\xf6n",lime:"Limegr\xf6n",mediumblue:"Mellanbl\xe5",navy:"Marinbl\xe5",blue:"Bl\xe5",lightblue:"Ljusbl\xe5",violet:"Violett"},directionality:{rtldir:"Riktning h\xf6ger till v\xe4nster",ltrdir:"Riktning v\xe4nster till h\xf6ger"},parlance:{menu:"Spr\xe5kmeny",set:"St\xe4ll in spr\xe5k",ar:"Arabiska",ca:"Katalanska",zh_cn:"Kinesiska (f\xf6renklad)",zh_tw:"Kinesiska (traditionell)",hr:"Kroatiska",cs:"Tjeckiska",da:"Danska",nl:"Holl\xe4ndska",en:"Engelska",en_au:"Engelska (Australien)",en_ca:"Engelska (Kanada)",en_gb:"Engelska (Storbritannien)",en_us:"Engelska (USA)",fa:"Persiska",fi:"Finska",fr:"Franska",fr_ca:"Franska (Kanada)",de:"Tyska",el:"Grekiska",he:"Hebreiska",hu:"Ungerska",it:"Italienska",ja:"Japanska",kk:"Kazakiska",ko:"Koreanska",no:"Norska",pl:"Polska",pt_br:"Portugisiska (Brasilien)",pt_pt:"Portugisiska (Portugal)",ro:"Rum\xe4nska",ru:"Ryska",sk:"Slovakiska",sl:"Slovenska",es:"Spanska",es_419:"Spanska (Latinamerika)",es_es:"Spanska (Spanien)",sv:"Svenska",tt:"Tatariska",th:"Thail\xe4ndska",tr:"Turkiska",uk:"Ukrainska"},taptoedit:"Tryck f\xf6r att redigera",plaincode:{dialog:{title:"Kodvy",editor:"Redigerare f\xf6r HTML-k\xe4lla"}},help:{dialog:{accessibility:"Tangentbordsnavigering",a11ycheck:"Tillg\xe4nglighetskontroll",about:"Om Textbox.io",markdown:"Nedskrivningsformatering",shortcuts:"Tangentbordsgenv\xe4gar"}},spelling:{context:{more:"Mer",morelabel:"Undermeny f\xf6r fler stavningsalternativ"},none:"Ingen",menu:"Stavningsspr\xe5k"},specialchar:{open:"Specialtecken",dialog:"Infoga specialtecken",latin:"Latin",insert:"Infoga",punctuation:"Interpunktion",currency:"Valutor","extended-latin-a":"Ut\xf6kad latin A","extended-latin-b":"Ut\xf6kad latin B",arrows:"Pilar",mathematical:"Matematiskt",miscellaneous:"Diverse",selects:"Valda tecken",grid:"Specialtecken"},insert:{"menu-button":"Infogningsmeny",menu:"Infoga",link:"L\xe4nk",image:"Bild",table:"Tabell",horizontalrule:"V\xe5gr\xe4t linjal",media:"Media"},media:{embed:"Inb\xe4ddningskod f\xf6r media",insert:"Infoga",placeholder:"Klistra in inb\xe4ddningskod h\xe4r."},wordcount:{open:"R\xe4kna ord",dialog:"R\xe4kna ord",counts:"Antal",selection:"Val",document:"Dokument",characters:"Tecken",charactersnospaces:"Tecken (inga mellanrum)",words:"Ord"},list:{unordered:{menu:"Alternativ f\xf6r oordnad lista",default:"Standard oordnad lista",circle:"Oordnad cirkel",square:"Oordnad fyrkant",disc:"Oordnad skiva"},ordered:{menu:"Alternativ f\xf6r ordnad lista",default:"Standard ordnad lista",decimal:"Ordnad decimal","upper-alpha":"Ordnade stora bokst\xe4ver","lower-alpha":"Ordnade sm\xe5 bokst\xe4ver","upper-roman":"Ordnade stora romerska","lower-roman":"Ordnade sm\xe5 romerska","lower-greek":"Ordnade sm\xe5 grekiska"}},tag:{inline:{class:"span ({0})"},img:"bild"},block:{normal:"Normal",p:"Avsnitt",h1:"Rubrik 1",h2:"Rubrik 2",h3:"Rubrik 3",h4:"Rubrik 4",h5:"Rubrik 5",h6:"Rubrik 6",div:"Div",pre:"Pre",li:"Listpost",td:"Cell",th:"Rubrikcell",styles:"Stilmeny",dropdown:"Block",describe:"Nuvarande stil {0}",menu:"Stilar",label:{inline:"Infogade stilar",table:"Tabellstilar",line:"Radstilar",media:"Mediastilar",list:"Liststilar",link:"L\xe4nkstilar"}},font:{"menu-button":"Teckensnittsmeny",menu:"Teckensnitt",face:"Typsnitt",size:"Teckensnittsstorlek",coloroption:"F\xe4rg",describe:"Nuvarande teckensnitt {0}",color:"Text",highlight:"Markera",stepper:{input:"Ange teckenstorlek",increase:"\xd6ka teckenstorleken",decrease:"Minska teckenstorleken"}},cog:{"menu-button":"Inst\xe4llningsmeny",menu:"Inst\xe4llningar",spellcheck:"Stavningskontroll",capitalisation:"Versaler",autocorrect:"Autokorrigera",linkpreviews:"L\xe4nkgranskningar",help:"Hj\xe4lp"},alignment:{toolbar:"Justeringsmeny",menu:"Justering",left:"V\xe4nsterjustera",center:"Centrera",right:"H\xf6gerjustera",justify:"Justeringsinriktning",describe:"Nuvarande inriktning {0}"},category:{language:"Spr\xe5kgrupp",undo:"\xc5ngra och g\xf6r om grupp",insert:"Infoga grupp",style:"Stilgrupp",emphasis:"Formateringsgrupp",align:"Inriktningsgrupp",listindent:"List- och indragsgrupp",format:"Teckensnittsgrupp",tools:"Verktygsgrupp",table:"Tabellgrupp",image:"Bildredigeringsgrupp"},action:{undo:"\xc5ngra",redo:"G\xf6r om",bold:"Fet",italic:"Kursiv",underline:"Understruken",strikethrough:"Genomstruken",subscript:"Neds\xe4nkt",superscript:"Upph\xf6jd",removeformat:"Ta bort formatering",bullist:"Oordnad lista",numlist:"Ordnad lista",indent:"Dra in mer",outdent:"Dra in mindre",blockquote:"Blockquote",fullscreen:"Helsk\xe4rm",search:"S\xf6k och ers\xe4tt - dialogruta",a11ycheck:"Kontrollera tillg\xe4nglighet",toggle:{fullscreen:"St\xe4ng helsk\xe4rm"}},table:{menu:"Infoga tabell","column-header":"Rubrikkolumn","row-header":"Rubrikrad",float:"Flytande inriktning",cell:{color:{border:"Kantlinjef\xe4rg",background:"Bakgrundsf\xe4rg"},border:{width:"Kantlinjebredd",stepper:{input:"St\xe4ll in kantlinjebredd",increase:"\xd6ka kantlinjebredd",decrease:"Minska kantlinjebredd"}}},context:{row:{title:"Undermeny f\xf6r rad",menu:"Rad",insertabove:"Infoga ovanf\xf6r",insertbelow:"Infoga under"},column:{title:"Undermeny f\xf6r kolumn",menu:"Kolumn",insertleft:"Infoga v\xe4nster",insertright:"Infoga h\xf6ger"},cell:{merge:"Sammanfoga celler",unmerge:"Dela celler"},table:{title:"Undermeny f\xf6r tabell",menu:"Tabell",properties:"Egenskaper",delete:"Ta bort"},common:{delete:"Ta bort",normal:"St\xe4ll in som Normal",header:"St\xe4ll in som Rubrik"},palette:{show:"Tabellredigeringsalternativ som finns tillg\xe4ngliga i verktygsf\xe4ltet",hide:"Tabellredigeringsalternativ som inte l\xe4ngre finns tillg\xe4ngliga"}},picker:{header:"Rubrikinst\xe4llning",label:"Tabellv\xe4ljare",describepicker:"Anv\xe4nd piltangenterna f\xf6r att st\xe4lla in tabellstorlek.  Tryck p\xe5 tabtangenten f\xf6r \xf6ppna inst\xe4llningar f\xf6r tabellrubrik. Tryck p\xe5 mellanslag eller enter f\xf6r att infoga tabell.",rows:"{0} h\xf6g",cols:"{0} bred"},border:"Kantlinje",summary:"Sammanfattning",dialog:"Tabellegenskaper",caption:"Tabelltext",width:"Bredd",height:"H\xf6jd"},align:{none:"Ingen inriktning",center:"Centrera",left:"V\xe4nsterjustera",right:"H\xf6gerjustera"},button:{ok:"OK",cancel:"Avbryt",close:"Avbrottsdialog"},banner:{close:"St\xe4ng ban\xe9r"},border:{on:"P\xe5",off:"Av",labels:{on:"Kantlinje p\xe5",off:"Kantlinje av"}},loading:{wait:"V\xe4nta"},toolbar:{more:"Mer",backbutton:"Bak\xe5t","switch-code":"V\xe4xla till Kodvy","switch-pencil":"V\xe4xla till Designvy"},link:{context:{edit:"Redigera l\xe4nk",follow:"\xd6ppna l\xe4nk",ignore:"Ignorera bruten l\xe4nk",remove:"Ta bort l\xe4nk"},dialog:{aria:{update:"Uppdatera l\xe4nk",insert:"Infoga l\xe4nk",properties:"Egenskaper f\xf6r l\xe4nk",quick:"Snabbalternativ"},autocomplete:{open:"L\xe4nk till Autoslutf\xf6r-lista tillg\xe4nglig. Forts\xe4tt skriva eller anv\xe4nd upp-/nedpilarna f\xf6r att v\xe4lja f\xf6rslag.",close:"L\xe4nk till Autoslutf\xf6r-lista st\xe4ngd",accept:"Valt l\xe4nkf\xf6rslag {0}"},edit:"Redigera",remove:"Ta bort",preview:"F\xf6rhandsgranska",update:"Uppdatera",insert:"Infoga",tooltip:"L\xe4nk"},properties:{dialog:{title:"Egenskaper f\xf6r l\xe4nk"},text:{label:"Text att visa",placeholder:"Skriv eller klistra in text att visa"},url:{label:"L\xe4nkens URL",placeholder:"Skriv eller klistra in URL"},title:{label:"Titel",placeholder:"Skriv eller klistra in l\xe4nktitel"},button:{remove:"Ta bort"},target:{label:"M\xe5l",none:"Ingen",blank:"Nytt f\xf6nster",top:"Hela sidan",self:"Samma ram",parent:"\xd6verordnad ram"}},anchor:{top:"B\xf6rjan av dokumentet",bottom:"Slutet av dokumentet"}},fileupload:{title:"Infoga bilder",tablocal:"Lokala filer",tabweburl:"Webbadress",dropimages:"Sl\xe4pp bilder h\xe4r",chooseimage:"V\xe4lj bild att ladda upp",web:{url:"Adress till webbild:"},weburlhelp:"Ange webbadress f\xf6r att se en bildf\xf6rhandsvisning. Det kan dr\xf6ja lite l\xe4ngre innan st\xf6rre bilder kan visas.",invalid1:"Vi hittar ingen bild p\xe5 den webbadress du anv\xe4nder.",invalid2:"Kontrollera om du angett din webbadress felaktigt.",invalid3:"Se till att den bild du visar \xe4r offentlig och inte l\xf6senordsskyddad eller tillh\xf6r ett privat n\xe4tverk."},image:{context:{properties:"Bildegenskaper",palette:{show:"Bildredigeringsalternativ som finns tillg\xe4ngliga i verktygsf\xe4ltet",hide:"Bildredigeringsalternativ som inte l\xe4ngre finns tillg\xe4ngliga"}},dialog:{title:"Bildegenskaper",fields:{align:"Flytande inriktning",url:"URL",urllocal:"Bilden har \xe4nnu inte sparats",alt:"Alternativ text",width:"Bredd",height:"H\xf6jd",constrain:{label:"Bibeh\xe5ll proportionerna",on:"L\xe5sta proportioner",off:"Ol\xe5sta proportioner"}}},menu:"Infoga bild","menu-button":"Infoga bildmeny","from-url":"Webbadress","from-camera":"Kamerarulle",toolbar:{rotateleft:"Rotera \xe5t v\xe4nster",rotateright:"Rotera \xe5t h\xf6ger",fliphorizontal:"V\xe4nd v\xe5gr\xe4t",flipvertical:"V\xe4nd lodr\xe4t",properties:"Bildegenskaper"},crop:{announce:"\xd6ppna besk\xe4rningsgr\xe4nssnittet. Tryck p\xe5 Enter f\xf6r att till\xe4mpa eller p\xe5 Esc. om du vill avbryta.",cancel:"Avbryta besk\xe4rnings\xe5tg\xe4rden",begin:"Besk\xe4r bild",apply:"Till\xe4mpa besk\xe4rningen",handle:{nw:"\xd6vre v\xe4nstra besk\xe4rningshandtaget",ne:"\xd6vre h\xf6gra besk\xe4rningshandtaget",se:"Nedre h\xf6gra besk\xe4rningshandtaget",sw:"Nedre v\xe4nstra besk\xe4rningshandtaget",shade:"Besk\xe4rningsmask"}}},units:{"amount-of-total":"{0} av {1}"},search:{menu:"S\xf6k och ers\xe4tt",field:{replace:"Ers\xe4ttningsf\xe4lt",search:"S\xf6kf\xe4lt"},search:"S\xf6k",previous:"F\xf6reg\xe5ende",next:"N\xe4sta",replace:"Ers\xe4tt","replace-all":"Ers\xe4tt alla",matchcase:"Matcha gemener/VERSALER"},mentions:{initiated:"Skapat omn\xe4mnande, forts\xe4tt att skriva f\xf6r typ",lookahead:{open:"Listruta f\xf6r typeahead",cancelled:"Avbrutet omn\xe4mnande",searching:"S\xf6ker efter resultat",selected:"Infogat omn\xe4mnande av {0}",noresults:"Inga resultat"}},cement:{dialog:{paste:{title:"Alternativa inklistringsformateringar",instructions:"V\xe4lj att beh\xe5lla eller ta bort formatering i det inklistrade inneh\xe5llet.",merge:"Beh\xe5ll formatering",clean:"Ta bort formatering"},flash:{title:"Importera lokal bild","trigger-paste":"Aktivera inklistring igen fr\xe5n tangentbordet f\xf6r att klistra in inneh\xe5ll med bilder.",missing:'Adobe Flash kr\xe4vs f\xf6r att importera bilder fr\xe5n Microsoft Office. Installera <a href="http://get.adobe.com/flashplayer/" target="_blank">Adobe Flash Player</a>.',"press-escape":'Tryck p\xe5 <span class="ephox-polish-help-kbd">ESC</span> om du vill ignorera lokala bilder och forts\xe4tta redigera.'}}},cloud:{error:{apikey:"Din API-nyckel \xe4r ogiltig.",domain:"Din dom\xe4n ({0}) st\xf6ds inte av din API-nyckel.",plan:"Du har \xf6verskridit antalet redigerarh\xe4mtningar tillg\xe4ngliga p\xe5 dit abonnemang. G\xe5 in p\xe5 webbplatsen f\xf6r att uppgradera."},dashboard:"\xd6ppna Instrumentpanelen f\xf6r administrat\xf6r"},errors:{paste:{notready:"Funktionen Ordimportering har inte laddats. V\xe4nta och f\xf6rs\xf6k igen.",generic:"Ett fel uppstod n\xe4r inneh\xe5llet klistrades in."},toolbar:{missing:{custom:{string:'Specialkommandon m\xe5ste ha egenskapen "{0}" och m\xe5ste vara en str\xe4ng'}},invalid:"Verktygsf\xe4ltets konfiguration \xe4r ogiltig ({0}). Se konsolen f\xf6r detaljerad information."},spelling:{missing:{service:"Hittade inte stavningstj\xe4nsten: ({0})."}},images:{edit:{needsproxy:"En proxy kr\xe4vs f\xf6r att redigera bilder fr\xe5n den h\xe4r dom\xe4nen: ({0})",proxyerror:"Det g\xe5r inte att kommunicera med proxyn f\xf6r att redigera denna bild. Se konsolen f\xf6r detaljerad information.",generic:"Ett fel intr\xe4ffade under bildredigeringen. Se konsolen f\xf6r detaljerad information."},disallowed:{local:"Inklistring av lokala bilder har inaktiverats. Lokala bilder har tagits bort fr\xe5n inklistrat inneh\xe5ll.",dragdrop:"Dra och sl\xe4pp har inaktiverats."},upload:{unknown:"Bilden kunde inte laddas upp",invalid:"Alla filer bearbetades inte - vissa var inte giltiga bilder",failed:"Bilden kunde inte laddas upp: ({0}).",cors:"Kunde inte kontakta bilduppladdningstj\xe4nsten. M\xf6jliga CORS-fel: ({0})"},missing:{service:"Hittade inte bildtj\xe4nsten: ({0}).",flash:"Din webbl\xe4sares s\xe4kerhetsinst\xe4llningar kan eventuellt f\xf6rhindra import av bilder."},import:{failed:"Vissa bilder kunde inte importeras.",unsupported:"Bildtypen st\xf6ds inte.",invalid:"Bilden \xe4r ogiltig."}},webview:{image:"Direkt kopierade bilder kan inte klistras in."},safari:{image:"Safari har inte st\xf6d f\xf6r direkt inklistring av bilder.",url:"Mer information om inklistring av bilder f\xf6r Safari",rtf:"Mer information om inklistring f\xf6r Safari"},flash:{crashed:"Bilderna har inte importerats eftersom Adobe Flash verkar ha slutat fungera. Det kan h\xe4nda n\xe4r man klistrar in stora dokument."},http:{400:"Felaktig f\xf6rfr\xe5gan: {0}",401:"Oauktoriserad: {0}",403:"F\xf6rbjuden: {0}",404:"Hittades inte: {0}",407:"Proxyautentisering kr\xe4vs: {0}",409:"Filkonflikt: {0}",413:"F\xf6r stor nyttolast: {0}",415:"Mediatypen st\xf6ds inte: {0}",500:"Internt serverfel: {0}",501:"Implementerades inte: {0}"}}}},b=function(a,b){var c=a.src.indexOf("?");return a.src.indexOf(b)+b.length===c},c=function(a){for(var b=a.split("."),c=window,d=0;d<b.length&&void 0!==c&&null!==c;++d)c=c[b[d]];return c},d=function(a,b){if(a){var d="data-main",e=a.getAttribute(d);if(e){a.removeAttribute(d);var f=c(e);if("function"==typeof f)return f;console.warn("attribute on "+b+" does not reference a global method")}else console.warn("no data-main attribute found on "+b+" script tag")}},e=function(a,c){var e=d(document.currentScript,c);if(e)return e;for(var f=document.getElementsByTagName("script"),g=0;g<f.length;g++)if(b(f[g],a)){var h=d(f[g],c);if(h)return h}throw"cannot locate "+c+" script tag"},f="2.1.0",g=e("tbio_sv.js","strings for language sv");g({version:f,strings:a})}();