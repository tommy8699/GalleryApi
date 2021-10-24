Gallery Web Project (Backend) 
=================

Požiadavky
------------
- nainštalovaný composer 
- minimalne PHP 7.2 

Stiahnutie projektu pomocou git
--------------------------------

- Príkazom git clone si naclonujeme (stiahneme) projekt do nášho zariadenia  
```
   git clone https://github.com/tommy8699/GalleryApp.git GalleryApp
```
- A následne zadáme príkaz git fetch, pre zistenie zmien na githube
 ```  
git fetch origin master
```
- Posledný git príkaz, ktorý použijeme je git pull, pre stiahnutie všetkých zmien z githubu
``` 
  git pull origin master
```

Inštalacia
------------
- Príkazom composer install nainštalujem všetky balíčky, ktoré v aplikácii používame 
 ```
  composer install 
 ```
- V projekte si je potrebne vytvoriť  ``` local.neon ``` v adresári  ```config ```, ktorý vzhľadom nato že nemáme pripojenie k DB
tak môže byť prázdny.  

Web Server Setup
----------------

- Spustíme si Php server pomocou príkazu: 
```
php -S localhost:8000 -t www
```
- A následne môžeme prejsť na adresu:
     http://localhost:8000

- Vytvaranie novej galerie spracuvavá Gallery presenter, kde uz pri vytvaraní obrázka vytvorí aj novú galeriu, po zadani cesty. (POST request) 

Podstranky:
-----------
**Gallery**
http://localhost:8000/gallery/nazovGalerie

Alebo zadaním názvu obrázka pri Delete requeste - ak potrebujeme vymazat obrázok
http://localhost:8000/gallery/nazovGalerie/images.jpg

**Images**
http://localhost:8000/images/width/height/názovObrazku
