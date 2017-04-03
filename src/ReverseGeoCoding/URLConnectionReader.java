package ReverseGeoCoding;
import java.net.*;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;
import java.io.*;

import org.json.*;

public class URLConnectionReader {
    public static void main(String[] args) throws Exception {
    	//String lat= "45.5405832";
    	//String lon= "-73.5965186";
        String lat1="",lon1="",id1="",type1="",address1="";
    	ArrayList<String> lat=new ArrayList<String>();
    	ArrayList<String> lon=new ArrayList<String>();
    	ArrayList<String> id=new ArrayList<String>();
    	ArrayList<String> type=new ArrayList<String>();
    	ArrayList<String> address=new ArrayList<String>();
        final String FILE_HEADER = "id,latitude,longitude,type,address";
        BufferedReader br = null;
		FileReader fr = null;
		BufferedWriter bw = null;
		FileWriter fw = null;
		fw = new FileWriter("C:\\typ.csv");
		fw.append(FILE_HEADER.toString());
		fw.append("\n");
		
		bw = new BufferedWriter(fw);
		try {

			fr = new FileReader("C:\\venues.psv");
			br = new BufferedReader(fr);

			String sCurrentLine;

			//br = new BufferedReader(new FileReader("C:\\venues.psv"));
			String ss[];
			int i=0,j=1;
			//bw.write("AShwin, Nair\n Nair, Ashwin");
			while ((sCurrentLine = br.readLine()) != null) {
				i++;
				if(i<3)
					continue;
				ss=sCurrentLine.split(" +");
				if(ss.length>=5)
				{
				lat1=ss[3];
				lon1=ss[5];
				id1=ss[1];
				
				}
				else
					continue;
				lat.add(lat1);
				lon.add(lon1);
				id.add(id1);
				
				URL oracle = new URL("https://maps.googleapis.com/maps/api/geocode/json?latlng="+lat1+","+lon1+"&key=AIzaSyBdnhx77xLj21YA8hT2SSmVldXensGe85U");
		        URLConnection yc = oracle.openConnection();
		        BufferedReader in = new BufferedReader(new InputStreamReader(
		                                    yc.getInputStream()));
		        String inputLine;
		        int f=0;
		        String res="";
		        String pre[];
		        StringBuffer buffer = new StringBuffer();
		        while ((inputLine = in.readLine()) != null) 
		        {
		        	buffer.append(inputLine);
/*		        	if(inputLine.contains("place_id"))
		        		{f=1;continue;}
		        	if(f==1)	
		        	{
		        		pre=inputLine.split(":");
		        		
		        		pre[1]=pre[1].substring(4, pre[1].length()-3);
		        		System.out.println(j);
		        		System.out.println(pre[1]);break;}
		        	*/
		        }
		        in.close();
		        //System.out.println(buffer);
		        JSONObject json = new JSONObject(buffer.toString());
		        JSONArray js = new JSONArray(json.get("results").toString());
		        
		        for (int k=0;k<1;k++) {
		        	JSONObject jsonOb = js.getJSONObject(k);
		        	JSONArray typesArr = (JSONArray) jsonOb.get("types");
		        	System.out.println(typesArr+"::"+ jsonOb.get("formatted_address"));
		        	//fw.append()
		        	//bw.write("ash,nair\n");
		        	type1=""+typesArr;
		        	address1=""+jsonOb.get("formatted_address");
		        	System.out.println(lat1+"................"+lon1);
		        	fw.append(id1);
					fw.append(",");
					fw.append(lat1);
					fw.append(",");
					fw.append(lon1);
					fw.append(",");
					fw.append(type1);
					fw.append(",");
					fw.append(address1);
					fw.append("\n");
		        }
				j++;
				
			}
			/*
			for(int l=0;l<id.size();l++)
			{
				fw.append(id.get(i));
				fw.append(",");
				fw.append(lat.get(i));
				fw.append(",");
				fw.append(lon.get(i));
				fw.append(",");
				fw.append(type.get(i));
				fw.append(",");
				fw.append(address.get(i));
				
			}
			*/
			fw.close();
		} catch (IOException e) {

			e.printStackTrace();

		}
    	
    }
}