��    f      L  �   |      �  g   �     		     "	     <	     C	     ^	  '   o	     �	      �	     �	  	   �	  D   �	  0   9
  ;  j
  M   �  E   �     :     H     ]     p  5   �  -   �  {   �  i   e  �  �     �     �  J   �  A        R     Y     s     �     �     �     �     �     �     �     �          !     4  �   I  f   �  �   X  �   �  �   {  	   �            N   $  O   s  B  �  3       :     G     V  5   \     �     �     �  m   �  2   H  ,   {  �   �  T        �     �     �               !  )   (     R  B   a     �     �     �     �     �     �               &  '   ;  S   c  F   �  �  �  2   �     �     �  4     �   :  E   �  (   #  *   L  (   w  d   �  1      	   7   �  A   b   �!     ,"     F"     ^"     f"     �"     �"  "   �"  #   �"     �"  	   #  �   #  /   �#  0  �#  M   %  L   j%     �%     �%     �%     �%  6   &  5   B&  �   x&  �   '  	  �'  
   �)     �)  ,   �)  _   *     x*     �*     �*     �*     �*  	   �*     �*     �*     �*     +  &    +  +   G+     s+     �+  �   �+  �   {,  �   -  �   �-  �   �.     :/     F/  $   M/  H   r/  Z   �/  �  0  �  �1     t3     �3     �3  A   �3     �3      4     $4  �   D4  T   �4  T   5  �   s5  o   @6  	   �6     �6     �6     �6     �6     7  :   7     U7  =   e7  
   �7     �7     �7     �7     �7      8     8     )8     B8  S   V8  h   �8  Q   9  �  e9  1   ;  (   =;     f;  =   j;  �   �;  P   �<  6   �<  "   =  '   /=  c   W=  '   �=     �=     X   V             D       ^   '   :          +                 !              K       J   0         .                  R   (          \   ?       *   W   `   8   T      c      [                  B      _   S       O   /   6   @            >   C   <       a   2                     F           5       7      Q   f   E          A              d   ]       4      9           	       L   G       
   H   U   "   ,   3   M   I   1   Y   =   ;      P   e   Z      )   $          %       N                 b   &   #   -    %s is the current name of the selected preset. Please choose a different name, or click 'Save' instead. %s records in the branch %s records in this branch (None) A preset name is required. Add cutoff point Added Hungarian and German localization Added Save &amp; Rename command Added install/uninstall features Added release log All trees Allows exporting a branch of a tree. Requires Clippings cart module! Are you sure you want to delete this preset: %s? Are you sure you want to uninstall this module?

(NOTE: all presets and module-related settings will be permanently deleted and the module will be disabled. Module files will not be removed. They must be manually deleted. The module can be reactivated on the 'Control Panel / Modules / Module administration' page.) As soon as you are ready to perform the initialization, click the link below. As soon as you are ready to perform the update, click the link below. Branch export Branch export config Branch export help Branch export module Branch export module - database initialization needed Branch export module - database update needed Branch export module helps you export a portion of a tree in a way that is not possible using the built-in export features. Branch export module needs a database update. Please visit the module's main page to perform this update! Branch export traverses the entire tree, starting from a specific individual (called pivot point). First it will select the immediate relatives of the pivot individual (e. g. parents, children, spouses, siblings). Then continues the traversal recursively with their relatives, processing them like the pivot point - unless they are one of the predefined blocking individuals (called cutoff points). The traversal will stop when all non-blocked individuals are processed. Branch preview Branch settings Clicking 'Delete' will now ask for confirmation before deleting the preset Clippings cart module is disabled. 'Export branch' will not work! Config Copy selected presets to: Cutoff point #%d: Cutoff point %d: Cutoff points: Cutoff: Delete Delete selected presets Delete selected presets? Export branch Export new branch from: Export preset branch: Export this branch First public release Fixed: 'Name' field contained the id of the preset (instead of the name) after clicking 'Preview'; 'Load preset' also lost its stored value, and was reset to '(None)'. Fixed: 'Unable to delete preset (None)' error message when pressing Delete without selecting a preset. Fixed: after saving/deleting/renaming a preset the preset list was refreshed incorrectly, preventing further rename/delete operations (until page refresh). Fixed: when clicking Delete, the confirmation dialog used the value of the 'Name' input field instead the name of the selected Preset. Fixed: when renaming a preset with the new name being identical to the current name, 'duplicate key' error message was displayed. Full name Help How branch export works Improved help section, as well as other changes/fixes to certain text elements Invalid pivot point selected: %s. No such individual found in the current tree. It is required to initialize the data table where the branch export module stores the branch presets. This initialization is done automatically, but we ask for your permission so you have a chance to back up your data. The update should not harm your data in any way, but it is always a good idea to create a backup first. It is required to update the data table where the branch export module stores the branch presets. This update is done automatically, but we ask for your permission so you have a chance to back up your data. The update should not harm your data in any way, but it always a good idea to create a backup first. Load preset: Manage presets Name: No branch export presets defined for this individual. No orphaned presets found. No pivot point selected. No preset selected. Note: Exporting the content of the branch requires that the Clippings cart module is installed and activated. Only administrators can initialize the data table. Only administrators can update the database. Only individuals and families can be used as cutoff points. Any individual or family in the tree can be used. Using a family as cutoff point is a shortcut for adding all individuals in that family as cutoff points. Only individuals can be used as pivot point. Any individual in the tree can be used. Orphaned Perform Initialization Perform Update Pivot individual Pivot point: Pivot: Please select a preset from the dropdown. Preset details Preset with the same name (%s) already exists for the target tree. Preview branch Release log Remove cutoff point Rename &amp; Save Save Save branch export preset Select orphaned Select preset(s): Select specific tree Settings are only available for admins. Several minor UI fixes and improvements for Branch export main page and config page The current user is not authorized to modify presets belonging to '%s' The pivot point is always included. Cutoff points are also included if they can be reached during the traversal, but the traversal algorithm will stop traversing the tree when it hits a cutoff point, and thus their relatives - that are not reachable using a different path - will not be included. If an individual is included in the branch, all linked records (families, media objects, sources, notes, repositories) are also added. The tree to which this preset belonged was deleted Total %s records in this tree Tree: Unable to delete preset '%s'. Preset does not exist? User authorization is now properly checked. Only users that are members of the tree can work with the branch export module and the presets belonging to that tree. Warning message is now displayed if Clippings cart module is disabled What records are included in the branch? What records can be used as cutoff points? What records can be used as pivot point? You are not authorized to access this module. You are not logged in as a member of the current tree. You can use an unlimited number of cutoff points. uninstall Project-Id-Version: 
POT-Creation-Date: 2017-07-18 14:34+0200
PO-Revision-Date: 2017-07-18 14:39+0200
Last-Translator: 
Language-Team: 
Language: hu_HU
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit
X-Generator: Poedit 2.0.2
X-Poedit-Basepath: ..
Plural-Forms: nplurals=2; plural=(n != 1);
X-Poedit-KeywordsList: translate
X-Poedit-SearchPath-0: .
 %s a sablon jelenlegi neve. Kérjük, válasszon másik nevet, vagy kattintson a 'Mentés' gombra! %s rekordok ezen az ágon %s rekord ezen az ágon (Nincs) Nevet kell adnia a sablonnak. Végpont hozzáadása Magyar és német fordítás Mentés &amp; Átnevezés funkció Telepítés/eltávolítás funkció Kiadási megjegyzések Minden fa Lehetővé teszi a családfa egyedi módon meghatározott részletének az exportálását. Az exportáláshoz a Metszési kosár modulra is szükség van! Biztosan törölni szeretné ezt a sablont: %s? Biztosan szeretné eltávolítani ezt a modult?

(MEGJEGYZÉS: ez a művelet minden sablont és modulspecifikus beállítást törölni fog. A modulfájlokat azonban nem törli, azokat manuálisan kell eltávolítani. A modult újra aktiválhatja a 'Vezérlőpult/Modulok/Moduladminisztráció' oldalon.) Ha készen áll az adattábla létrehozására, kattintson az alábbi linkre! Ha készen áll az adattábla frissítésére, kattintson az alábbi linkre! Branch export Branch export konfiguráció Branch export súgó Branch export modul Branch export modul - az adatbázist létre kell hozni Branch export modul - az adatbázist frissíteni kell A Branch export modul segítségével a családfa egy részét exportálhatja olyan módon, ahogy a Webtrees beépített exportálási funkcióival nem lehetséges. A Branch export modul további használatához az adatbázis frissítése szükséges. Kérjük, keresse fel a modul főoldalát a frissítés végrehajtásához! A Branch export bejárja az egész fát, a kiindulópontként megjelölt személytől kezdve. Először kiválasztja a kiindulópont közvetlen hozzátartozóit (pl. gyermek, szülő, testvér, házastárs). Ezután rekurzív módon folytatja a fa bejárását, a kiindulóponthoz hasonló módon feldolgozva a rokonokat is - kivéve, ha azok a megadott végpontok közé tartoznak. A fa bejárása leáll, ha az összes olyan személyt feldolgozta, melyek elérését a végpontként megadott személyek nem blokkolták. Előnézet Az ág beállításai A 'Törlés' mostantól megerősítést kér A Metszési kosár (Clippings cart) module nincs aktiválva. Az exportálás nem fog működni! Beállítások Kijelöltek másolása ide: %d. végpont: %d. végpont: Végpontok: Végpont: Törlés Kijelöltek törlése Törli a kijelölt sablonokat? Exportálás Új ág exportálása innen kiindulva: Ág exportálása sablon létező alapján: Ág exportálása Az első nyilvános verzió Hibajavítás: Az 'Előnézet' gombra történő kattintás után a 'Név' mezőben a sablon azonosítója jelent meg a neve helyett. A 'Sablon betöltése' elvesztett tárolt értékét, és visszaállt '(Nincs)'-re. Hibajavítás: 'Unable to delete preset (None)'  hibaüzenet jelent meg, ha a 'Törlés' gombra kattintott, de nem választott sablont. Hibajavítás: sablon mentése/törlése/átnevezése után a sablonlista helytelenül frissült, ezért az oldal újratöltéséig nem működött a törlés és az átnevezés funkció. Hibajavítás: ha a 'Törlés'-re kattintott, a megerősítést kérő párbeszédablak tévesen a 'Név' mező értékét jelenítette meg a törlendő sablon neveként, nem a legörülő menüből kiválasztott sablon tényleges nevét. Hibajavítás: egy sablon átnevezése során, amennyiben az új név megegyezett a jelenlegivel, 'duplicate entry' hibaüzenet jelent meg. Teljes név Súgó Így működik a Branch export modul Javított Súgó, valamint kisebb javítások egyéb szöveges elemekhez Érvénytelen kiindulópontot választott: %s. Nincs ilyen személy ebben a családfában. A modul használata előtt létre kell hozni azt az adattáblát, melyben a modul a mentett beállításokat tárolni fogja. Ezt a modul automatikusan megteszi, de a művelet végrehajtásához az engedélyét kérjük, hogy biztonsági mentést tudjon készíteni adatairól. A frissítés semmilyen módon nem veszélyezteti az adatait, de a biztonság kedvéért mégis azt javasoljuk, hogy készítsen biztonsági mentést. A modul további használatához frissíteni kell az adattáblát, melyben a modul a mentett beállításokat tárolja. A frissítést a modul automatikusan elvégzi, de a művelet végrehajtásához az engedélyét kérjük, hogy biztonsági mentést tudjon készíteni adatairól. A frissítés semmilyen módon nem veszélyezteti az adatait, de a biztonság kedvéért mégis azt javasoljuk, hogy készítsen biztonsági mentést. Sablon betöltése: Mentett sablonok kezelése Név: Még nem hozott létre exportálási sablont ehhez a személyhez. Nincs elárvult sablon. Nem választott kiindulópontot. Nem jelölt ki egy sablont sem. Megjegyzés: az exportálás végrehajtásához a Metszési kosár (Clippings cart) modul telepítése és aktiválása szükséges. Ezt a műveletet csak adminisztrátor jogosultságú felhasználó hajthatja végre. Ezt a műveletet csak adminisztrátor jogosultságú felhasználó hajthatja végre. Csak személyek és családok lehetnek végpontok. Bármely személy vagy család lehet végpont. Amennyiben egy családot vesz fel végpontként, akkor a család minden tagja végpontnak fog számítani. Csak személyek lehetnek kiindulópontok. A családfában megtalálható bármely személy lehet kiindulópont. Elárvult Adattábla létrehozása Frissítés végrehajtása Kiindulópont Kiindulópont: Kiindulópont: Kérjük, válasszon egy sablont a legördülő menüből! A sablon adatai Már létezik azonos nevű (%s) sablon ebben a családfában. Előnézet Kiadási megjegyzések Végpont eltávolítása Átnevezés &amp; mentés Mentés Sablon mentése Elárvultak kijelölése Sablon(ok) kijelölése: Válasszon egy fát A beállításokat csak adminisztrátor jogosultságú felhasználó szerkesztheti. A főoldal és a beállítások felhasználói felületét érintő kisebb javítások és fejlesztések A felhasználó nem jogosult a(z) '%s' fához tartozó sablonok szerkesztésére. A kiindulópontot mindenképpen. A végpontokat is felveszi, amennyiben a fa bejárása során elérhetők. A bejáró algoritmus a végpontoknál megáll, és így a végpontok rokonai - amennyiben nem érhetők el más útvonalon - nem kerülnek bele az exportált ágba. Amennyiben egy személy belekerül az exportálandó ágba, minden kapcsolt rekord (család, média, forrás, megjegyzés, repozitórium) bekerül. Törölték a fát, melyhez ez a sablon tartozott %s rekordok száma ebben a családfában Fa: Nem sikerült a(z) '%s' sablon törlése. Nincs ilyen sablon? A felhasználói jogosultságokat most már megfelelően ellenőrzi a program. Csak az adott fa tagjaként bejelentkezett felhasználók érhetik el a modul funkcióit, és módosíthatják a fához tartozó sablonokat. Figyelmeztető üzenet jelenik meg, ha a Metszési kosár modul nincs aktiválva Mely rekordokat veszi fel a modul az exportált ágba? Mely rekordok lehetnek végpontok? Mely rekordok lehetnek kiindulópontok? Ön nem jogosult a modul használatára. Kérjük, jelentkezzen be mint a jelenleg aktív fa tagja. Korlátlan számú végpontot megadhat. eltávolítás 