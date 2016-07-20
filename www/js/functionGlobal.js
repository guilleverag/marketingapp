/*****************
**		FORMATEOS DE NUMEROS
**********************/
function number_format (number, decimals, dec_point, thousands_sep) {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = (s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)!='')?s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep): '00';
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}


var lsImgCss={
'A-F':{'img':'verdel.png','explain':'Active Foreclosed'},
'A-F-S':{'img':'verdel_s.png','explain':'Active Foreclosed Sold'},
'A-P':{'img':'verdel.png','explain':'Active Pre-Foreclosed'},
'A-P-S':{'img':'verdel_s.png','explain':'Active Pre-Foreclosed Sold'}, 
'A-N':{'img':'verdeb.png','explain':'Active'},
'A-N-S':{'img':'verdeb_s.png','explain':'Active Sold'},
'P-F': {'img':'grisl.png','explain':'Non-Active Foreclosed'},
'P-F-S': {'img':'grisl_s.png','explain':'Non-Active Foreclosed Sold'},
'P-P': {'img':'grisl.png','explain':'Non-Active Pre-Foreclosed'},
'P-P-S': {'img':'grisl_s.png','explain':'Non-Active Pre-Foreclosed Sold'},
'P-N': {'img':'grisb.png','explain':'Non-Active'},
'P-N-S': {'img':'grisb_s.png','explain':'Non-Active Sold'},
'CC-F': {'img':'cielol.png','explain':'By Owner Foreclosed'},
'CC-F-S': {'img':'cielol_s.png','explain':'By Owner Foreclosed Sold'},
'CC-P': {'img':'cielol.png','explain':'By Owner Pre-Foreclosed'},
'CC-P-S': {'img':'cielol_s.png','explain':'By Owner Pre-Foreclosed Sold'},
'CC-N': {'img':'cielo.png','explain':'By Owner'},
'CC-N-S': {'img':'cielo_s.png','explain':'By Owner Sold'},
'CS-P': {'img':'marronl.png','explain':'Closed Sale Pre-Foreclosed'},
'CS-P-S': {'img':'marronl_s.png','explain':'Closed Sale Pre-Foreclosed Sold'},
'CS-F': {'img':'marronl.png','explain':'Closed Sale Foreclosed'},
'CS-F-S': {'img':'marronl_s.png','explain':'Closed Sale Foreclosed Sold'},
'CS-N': {'img':'marronb.png','explain':'Closed Sale'},
'CS-N-S': {'img':'marronb_s.png','explain':'Closed Sale Sold'},
'PS-P': {'img':'grisl.png','explain':'Non-Active Pre-Foreclosed'},
'PS-P-S': {'img':'grisl_s.png','explain':'Non-Active Pre-Foreclosed Sold'},
'PS-F': {'img':'grisl.png','explain':'Non-Active Foreclosed'},
'PS-F-S': {'img':'grisl_s.png','explain':'Non-Active Foreclosed Sold'},
'PS-N': {'img':'grisb.png','explain':'Non-Active'},
'PS-N-S': {'img':'grisb_s.png','explain':'Non-Active Sold'},
'SP-': {'img':'grisdiamante.png','explain':'Unknow'},
'T-P': {'img':'grisl.png','explain':'Non-Active Pre-Foreclosed'},
'T-P-S': {'img':'grisl_s.png','explain':'Non-Active Pre-Foreclosed Sold'},
'T-F': {'img':'grisl.png','explain':'Non-Active Foreclosed'},
'T-F-S': {'img':'grisl_s.png','explain':'Non-Active Foreclosed Sold'},
'T-N': {'img':'grisb.png','explain':'Non-Active'},
'T-N-S': {'img':'grisb_s.png','explain':'Non-Active Sold'},
'R-P': {'img':'grisl.png','explain':'Non-Active Pre-Foreclosed'},
'R-P-S': {'img':'grisl_s.png','explain':'Non-Active Pre-Foreclosed Sold'},
'R-F': {'img':'grisl.png','explain':'Non-Active Foreclosed'},
'R-F-S': {'img':'grisl_s.png','explain':'Non-Active Foreclosed Sold'},
'R-N': {'img':'grisb.png','explain':'Non-Active'},
'R-N-S': {'img':'grisb_s.png','explain':'Non-Active Sold'},
'E-P': {'img':'grisl.png','explain':'Non-Active Pre-Foreclosed'},
'E-P-S': {'img':'grisl_s.png','explain':'Non-Active Pre-Foreclosed Sold'},
'E-F': {'img':'grisl.png','explain':'Non-Active Foreclosed'},
'E-F-S': {'img':'grisl_s.png','explain':'Non-Active Foreclosed Sold'},
'E-N': {'img':'grisb.png','explain':'Non-Active'},
'E-N-S': {'img':'grisb_s.png','explain':'Non-Active Sold'},
'C-P': {'img':'grisl.png','explain':'Non-Active Pre-Foreclosed'},
'C-P-S': {'img':'grisl_s.png','explain':'Non-Active Pre-Foreclosed Sold'},
'C-F': {'img':'grisl.png','explain':'Non-Active Foreclosed'},
'C-F-S': {'img':'grisl_s.png','explain':'Non-Active Foreclosed Sold'},
'C-N': {'img':'grisb.png','explain':'Non-Active'},
'C-N-S': {'img':'grisb_s.png','explain':'Non-Active Sold'},
'Q-N': {'img':'grisb.png','explain':'Non-Active'},
'Q-N-S': {'img':'grisb_s.png','explain':'Non-Active Sold'},
'Q-P': {'img':'grisl.png','explain':'Non-Active Pre-Foreclosed'},
'Q-P-S': {'img':'grisl_s.png','explain':'Non-Active Pre-Foreclosed Sold'},
'Q-F': {'img':'grisl.png','explain':'Non-Active Foreclosed'},
'Q-F-S': {'img':'grisl_s.png','explain':'Non-Active Foreclosed Sold'},
'W-N': {'img':'grisb.png','explain':'Non-Active'},
'W-N-S': {'img':'grisb_s.png','explain':'Non-Active Sold'},
'W-F': {'img':'grisl.png','explain':'Non-Active Foreclosed'},
'W-F-S': {'img':'grisl_s.png','explain':'Non-Active Foreclosed Sold'},
'W-P': {'img':'grisl.png','explain':'Non-Active Pre-Foreclosed'},
'W-P-S': {'img':'grisl_s.png','explain':'Non-Active Pre-Foreclosed Sold'},
'X-N': {'img':'grisb.png','explain':'Non-Active'},
'X-N-S': {'img':'grisb_s.png','explain':'Non-Active Sold'},
'X-P': {'img':'grisl.png','explain':'Non-Active Pre-Foreclosed'},
'X-P-S': {'img':'grisl_s.png','explain':'Non-Active Pre-Foreclosed Sold'},
'X-F': {'img':'grisl.png','explain':'Non-Active Foreclosed'},
'X-F-S': {'img':'grisl_s.png','explain':'Non-Active Foreclosed Sold'},
'SUBJECT': {'img':'verdetotal.png','explain':'Subject'},
'USER_CAR': {'img':'xxima.png','explain':'User'},
'B-N': {'img':'grisb.png','explain':'Non-Active'},
'B-N-S': {'img':'grisb_s.png','explain':'Non-Active Sold'},
'B-P': {'img':'grisl.png','explain':'Non-Active Pre-Foreclosed'},
'B-P-S': {'img':'grisl_s.png','explain':'Non-Active Pre-Foreclosed Sold'},
'B-F': {'img':'grisl.png','explain':'Non-Active Foreclosed'},
'B-F-S': {'img':'grisl_s.png','explain':'Non-Active Foreclosed Sold'},
'N-N': {'img':'grisb.png','explain':'Non-Active'},
'N-N-S': {'img':'grisb_s.png','explain':'Non-Active Sold'},
'N-P': {'img':'grisl.png','explain':'Non-Active Pre-Foreclosed'},
'N-P-S': {'img':'grisl_s.png','explain':'Non-Active Pre-Foreclosed Sold'},
'N-F': {'img':'grisl.png','explain':'Non-Active Foreclosed'},
'N-F-S': {'img':'grisl_s.png','explain':'Non-Active Foreclosed Sold'}};
var indImgCss='';

function getCasita(status,pendes,sold)
{
  if(pendes=='L') pendes='N';
  if(sold=='S') sold='-S'; else sold='';
   switch (status.toUpperCase())
   {
    case "A":
     indImgCss="A-"+pendes+sold;     
     break;
    case "P":
     indImgCss="P-"+pendes+sold;
     break;
    case "CC":    
     indImgCss="CC-"+pendes+sold;
     break;
    case "CS":    
     indImgCss="CS-"+pendes+sold;
     break;
    case "PS":
     indImgCss="PS-"+pendes+sold;
     break;
    case "SP":
     indImgCss="SP-"+pendes+sold;
     break;
    case "T":
     indImgCss="T-"+pendes+sold;
     break;
    case "R":
     indImgCss="R-"+pendes+sold;
     break;
    case "E":
     indImgCss="E-"+pendes+sold;
     break;
    case "C":
     indImgCss="C-"+pendes+sold;
     break;
    case "Q":
     indImgCss="Q-"+pendes+sold;
     break;
    case "R":
     indImgCss="R-"+pendes+sold;
     break;
    case "T":
     indImgCss="T-"+pendes+sold;
     break;
    case "W":
     indImgCss="W-"+pendes+sold;
     break;
    case "X":
     indImgCss="X-"+pendes+sold;
     break;
    case "B":
     indImgCss="B-"+pendes+sold;
     break;
    case "N":
     indImgCss="N-"+pendes+sold;
     break;
    case "SUBJECT":
     indImgCss="SUBJECT";break
   }

 
}

function getPointColor(status,pendes,sold)
{
		if(pendes=='L') pendes='N';
		if(sold=='S') sold='-S'; else sold='';
			switch (status.toUpperCase())
			{
				case "A":
					return "A-"+pendes+sold;					
					break;
				case "P":
					return "P-"+pendes+sold;
					break;
				case "CC":				
					return "CC-"+pendes+sold;
					break;
				case "CS":				
					return "CS-"+pendes+sold;
					break;
				case "PS":
					return "PS-"+pendes+sold;
					break;
				case "SP":
					return "SP-"+pendes+sold;
					break;
				case "T":
					return "T-"+pendes+sold;
					break;
				case "R":
					return "R-"+pendes+sold;
					break;
				case "E":
					return "E-"+pendes+sold;
					break;
				case "C":
					return "C-"+pendes+sold;
					break;
				case "Q":
					return "Q-"+pendes+sold;
					break;
				case "R":
					return "R-"+pendes+sold;
					break;
				case "T":
					return "T-"+pendes+sold;
					break;
				case "W":
					return "W-"+pendes+sold;
					break;
				case "X":
					return "X-"+pendes+sold;
					break;
				case "B":
					return "B-"+pendes+sold;
					break;
				case "N":
					return "N-"+pendes+sold;
					break;
				case "SUBJECT":
					return "SUBJECT";break
					break;
				default:
					return "SUBJECT";
			}
			
}


var lsHexCssPoint={
'A-F':{color:'#4AED3C', border:'#5eab1f'},
'A-F-S':{color:'#4AED3C', border:'#CE4DC3'},
'A-P':{color:'#4AED3C', border:'#CE4DC3'},
'A-P-S':{color:'#4AED3C', border:'#CE4DC3'},	
'A-N':{color:'#4AED3C', border:'#000'},
'A-N-S':{color:'#4AED3C', border:'#FE0202'},
'P-F':{color:'#006600', border:'#000'},
'P-F-S':{color:'#006600', border:'#000'},
'P-P':{color:'#006600', border:'#000'},
'P-P-S':{color:'#006600', border:'#FE0202'},
'P-N':{color:'#006600', border:'#000'},
'P-N-S':{color:'#006600', border:'#FE0202'},
'CC-F':{color:'#0099FF', border:'#CE4DC3'},
'CC-F-S':{color:'#0099FF', border:'#FE0202'},
'CC-P':{color:'#0099FF', border:'#CE4DC3'},
'CC-P-S':{color:'#0099FF', border:'#FE0202'},
'CC-N':{color:'#0099FF', border:'#CE4DC3'},
'CC-N-S':{color:'#0099FF', border:'#FE0202'},
'CS-P': {color:'#D2555D', border:'#CE4DC3'},
'CS-P-S':{color:'#D2555D', border:'#FE0202'},
'CS-F': {color:'#D2555D', border:'#CE4DC3'},
'CS-F-S': {color:'#D2555D', border:'#FE0202'},
'CS-N':{color:'#D2555D', border:'#CE4DC3'},
'CS-N-S':{color:'#D2555D', border:'#FE0202'},
'PS-P':{color:'#006600', border:'#000'},
'PS-P-S': {color:'#006600', border:'#FE0202'},
'PS-F':{color:'#006600', border:'#000'},
'PS-F-S':{color:'#006600', border:'#FE0202'},
'PS-N':{color:'#006600', border:'#000'},
'PS-N-S':{color:'#006600', border:'#FE0202'},
'SP-':{color:'#006600', border:'#000'},
'T-P': {color:'#006600', border:'#000'},
'T-P-S': {color:'#006600', border:'#FE0202'},
'T-F': {color:'#006600', border:'#000'},
'T-F-S': {color:'#006600', border:'#FE0202'},
'T-N':{color:'#006600', border:'#000'},
'T-N-S':{color:'#006600', border:'#FE0202'},
'R-P':{color:'#006600', border:'#000'},
'R-P-S': {color:'#006600', border:'#FE0202'},
'R-F': {color:'#006600', border:'#000'},
'R-F-S':{color:'#006600', border:'#FE0202'},
'R-N':{color:'#006600', border:'#000'},
'R-N-S': {color:'#006600', border:'#FE0202'},
'E-P':{color:'#006600', border:'#000'},
'E-P-S':{color:'#006600', border:'#FE0202'},
'E-F':{color:'#006600', border:'#000'},
'E-F-S': {color:'#006600', border:'#FE0202'},
'E-N': {color:'#006600', border:'#000'},
'E-N-S':{color:'#006600', border:'#FE0202'},
'C-P': {color:'#006600', border:'#000'},
'C-P-S': {color:'#006600', border:'#FE0202'},
'C-F': {color:'#006600', border:'#000'},
'C-F-S':{color:'#006600', border:'#FE0202'},
'C-N':{color:'#006600', border:'#000'},
'C-N-S':{color:'#006600', border:'#FE0202'},
'Q-N': {color:'#006600', border:'#000'},
'Q-N-S': {color:'#006600', border:'#FE0202'},
'Q-P': {color:'#006600', border:'#000'},
'Q-P-S': {color:'#006600', border:'#FE0202'},
'Q-F': {color:'#006600', border:'#000'},
'Q-F-S': {color:'#006600', border:'#FE0202'},
'W-N':{color:'#006600', border:'#000'},
'W-N-S': {color:'#006600', border:'#FE0202'},
'W-F': {color:'#006600', border:'#000'},
'W-F-S':{color:'#006600', border:'#FE0202'},
'W-P':{color:'#006600', border:'#000'},
'W-P-S': {color:'#006600', border:'#FE0202'},
'X-N': {color:'#006600', border:'#000'},
'X-N-S':{color:'#006600', border:'#FE0202'},
'X-P': {color:'#006600', border:'#000'},
'X-P-S': {color:'#006600', border:'#FE0202'},
'X-F':{color:'#006600', border:'#000'},
'X-F-S': {color:'#006600', border:'#FE0202'},
'SUBJECT':{color:':#5eab1f', border:'#000'},
'USER_CAR':{color:':#5eab1f', border:'#000'},
'B-N':{color:'#006600', border:'#000'},
'B-N-S':{color:'#006600', border:'#000'},
'B-P':{color:'#006600', border:'#000'},
'B-P-S': {color:'#006600', border:'#FE0202'},
'B-F': {color:'#006600', border:'#000'},
'B-F-S': {color:'#006600', border:'#FE0202'},
'N-N': {color:'#868686', border:'#868686'},
'N-N-S': {color:'#868686', border:'#CE4DC3'},
'N-P':{color:'#868686', border:'##CE4DC3'},
'N-P-S':{color:'#868686', border:'#FE0202'},
'N-F':{color:'#868686', border:'#CE4DC3'},
'N-F-S': {color:'#868686', border:'#FE0202'}};
