# Chemical Compound Database / Directed Learning

This project processes a collection of molecules from `.sdf` (Structure Data File) format, extracted from [Edulis](https://pmc.ncbi.nlm.nih.gov/articles/PMC3013767/). It creates a structured database that allows searching for molecules based on their properties.

## File Structure

### **Python Scripts**git 
- `dumpmanuf.py` - Extracts manufacturer names from the dataset.
- `Compounds_remainder_pop.py` - Populates the `compounds` table with remaining data.
- `Compounds_tablepop.py` - Inserts compound data into the database.
- `manu_split.py` - Splits and processes manufacturer information.

### **SQL Scripts**
- `maketables.sql` - Creates tables for `compounds` and `manufacturers`.
- `newCompoundsTable.sql` - Handles cases where missing default values prevent insertion.

### **Java Code**
- `SDFprop.java` - Parses and processes `.sdf` files to extract molecular properties.

## Database Structure

The project contains two primary tables:

- **`Compounds` Table**: Stores chemical compound data, including molecular properties such as weight, hydrogen bond donors/acceptors, and other descriptors.
- **`Manufacturers` Table**: Contains manufacturer details, including name and contact information.