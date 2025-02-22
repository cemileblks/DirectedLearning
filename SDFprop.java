import java.io.*;
import java.math.BigDecimal;
import java.math.RoundingMode;
import javax.vecmath.Point3d;
import java.util.Hashtable;
import java.util.Map;

// import CDK class
import org.openscience.cdk.Molecule;
import org.openscience.cdk.DefaultChemObjectBuilder;
import org.openscience.cdk.io.iterator.IteratingMDLReader;
import org.openscience.cdk.io.MDLWriter;
import org.openscience.cdk.interfaces.*;
import org.openscience.cdk.CDKConstants;

public class SDFprop {

	// This program will start from here
	public static void main(String args[]) {
		double distance = 0.0;
		double max_dist = 0.0;
		if (args.length != 1) {
			// Wrong arguments output message
			System.out.println("Usage: java SDFprop sdffile");
			System.exit(0);
		}
		// String for SDfile Multi-SDFile allowed
		String SDFile = args[0];
		FileReader fr = null;
		BufferedReader br = null;

		// get molecule object using CDK
		try {
			IteratingMDLReader MDLReader = new IteratingMDLReader(new FileInputStream(SDFile),
					DefaultChemObjectBuilder.getInstance());
			while (MDLReader.hasNext()) {
				// (Molecule)MDLReader.next() to get a molecule object
				// total_distance = cd.get3DWienerIndex((Molecule)MDLReader.next());
				Molecule mymol;
				mymol = (Molecule) MDLReader.next();
				String Name = "";
				Map props;
				Name = new String(String.valueOf(mymol.getProperty(CDKConstants.TITLE)));
				int natoms = mymol.getAtomCount();
				props = mymol.getProperties();
				// System.out.format("%s %n",props.toString());
				int nc = 0;
				int no = 0;
				int ns = 0;
				int nn = 0;
				for (int i = 0; i < natoms; ++i) {
					IAtom myatom = mymol.getAtom(i);
					String s1 = myatom.getSymbol();
					if (s1.equals("C"))
						++nc;
					if (s1.equals("N"))
						++nn;
					if (s1.equals("O"))
						++no;
					if (s1.equals("S"))
						++ns;
				}
				System.out.format("%s %d %d %d %d %d %n", Name, natoms, nc, nn, no, ns);
			}
		} catch (Exception e) {
			System.out.println(e.toString());
		}
	}
}
