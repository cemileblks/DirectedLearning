CREATE database if not exists s2756532_website ;
use s2756532_website ;

CREATE TABLE `Manufacturers` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT KEY,
`name` VARCHAR(80) NOT NULL,
`contact` VARCHAR(80)
);
CREATE TABLE `Compounds` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT KEY,
`natm` INT  NOT NULL,
`ncar` INT  NOT NULL,
`nnit` INT  NOT NULL,
`noxy` INT  NOT NULL,
`nsul` INT  NOT NULL,
`ncycl` INT NOT NULL ,
`nhdon` INT NOT NULL ,
`nhacc` INT NOT NULL ,
`nrotb` INT NOT NULL ,
`ManuID` INT NOT NULL ,
`catn` VARCHAR(15) NOT NULL ,
`mw` FLOAT NOT NULL,
`TPSA` FLOAT NOT NULL,
`XLogP` FLOAT NOT NULL
); 
 
