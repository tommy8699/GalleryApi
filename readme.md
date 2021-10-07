Gallery Web Project (Backend) 
=================

Požiadavky
------------
- nainštalovaný composer 
- minimalne PHP 7.2 

Stiahnutie projektu pomocou git
--------------------------------

- Príkazom git clone si naclonujeme projekt do nášho zariadenia 
   git clone 
- A následne zadáme príkaz git fetch, pre zistenie zmien na githube
   git fetch origin master
- Posledný git príkaz, ktorý použijeme je git pull, pre stiahnutie všetkých zmien z githubu
   git pull origin master

Inštalacia
------------
- Príkazom composer install nainštalujem všetky balíčky, ktoré v aplikácii používame 
   composer install 

Web Server Setup
----------------

- Spustíme si Php server pomocou príkazu: 
	php -S localhost:8000 -t www

- A následne môžeme prejsť na adresu:
     http://localhost:8000

Podstranky:

Gallery 
http://localhost:8000/gallery/nazovGalerie

Images
http://localhost:8000/images/width/height/názovObrazku
