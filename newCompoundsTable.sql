DROP TABLE Compounds ;
CREATE TABLE `Compounds` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT KEY,
`natm` INT  NOT NULL default 0 ,
`ncar` INT  NOT NULL default 0 ,
`nnit` INT  NOT NULL default 0 ,
`noxy` INT  NOT NULL default 0 ,
`nsul` INT  NOT NULL default 0 ,
`ncycl` INT NOT NULL default 0 ,
`nhdon` INT NOT NULL default 0 ,
`nhacc` INT NOT NULL default 0 ,
`nrotb` INT NOT NULL default 0 ,
`ManuID` INT NOT NULL default 0 ,
`catn` VARCHAR(15) NOT NULL ,
`mw` FLOAT NOT NULL,
`TPSA` FLOAT NOT NULL,
`XLogP` FLOAT NOT NULL
); 
 
