import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.security.MessageDigest;

public class MyClass
{
    
    public static void main(String[] args)
        throws Exception
    {
        String key = "Huawei@123";
        File file = new File("test.txt");
        
        try
        {
            byte[] randomkeyBytes = new byte[256];
            FileInputStream fis = new FileInputStream(file);
            fis.read(randomkeyBytes);
            fis.close();
            
            int index = 256;
            for (; index > 0; index--)
            {
                if (0 != randomkeyBytes[index - 1])
                    break;
            }
            
            byte[] keyBytes = new byte[index];
            System.arraycopy(randomkeyBytes, 0, keyBytes, 0, index);
            
            String encodePwd = base64Encode(keyBytes);
            MessageDigest md = MessageDigest.getInstance("SHA-256");
            md.update(key.getBytes("UTF-8"));
            byte[] encryptPassword = md.digest();
            
            StringBuilder sBuilder = new StringBuilder();
            sBuilder.append(bytes2HexString(encryptPassword));
            String password = sBuilder.toString();
            sBuilder = new StringBuilder();
            
            sBuilder.append(bytes2HexString(getFromBASE64(encodePwd)));
            password += sBuilder.toString();
            md.update(password.getBytes("UTF-8"));
            encryptPassword = md.digest();
            
            sBuilder.append(bytes2HexString(encryptPassword));
            String ep = base64Encode(encryptPassword);
            byte[] encrptPasswordBytes = getFromBASE64(null == ep ? "" : ep);
            
            FileOutputStream out = new FileOutputStream(file);
            out.write(encrptPasswordBytes);
            out.flush();
            out.close();
        }
        catch (UnsupportedEncodingException e)
        {
            e.printStackTrace();
        }
        catch (IOException e)
        {
            e.printStackTrace();
        }
        
    }
    
    public static String base64Encode(byte[] btData)
    {
        int iLen = 0;
        boolean l_bFlag;
        int l_iGroup;
        char[] l_szData;
        byte[] l_btTmp;
        
        int ii;
        int jj;
        int kk;
        
        String l_stEncoding = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
        
        if (btData == null)
        {
            return null;
        }
        
        if ((iLen <= 0) || (iLen > btData.length))
        {
            iLen = btData.length;
        }
        
        l_bFlag = ((iLen % 3) == 0);
        
        l_iGroup = iLen / 3;
        
        ii = l_iGroup;
        
        if (!l_bFlag)
        {
            ii++;
        }
        
        l_szData = new char[4 * ii];
        l_btTmp = new byte[3];
        
        for (ii = 0, jj = 0, kk = 0; ii < l_iGroup; ii++)
        {
            l_btTmp[0] = btData[kk++];
            l_btTmp[1] = btData[kk++];
            l_btTmp[2] = btData[kk++];
            
            l_szData[jj++] = l_stEncoding.charAt((l_btTmp[0] >> 2) & 0x3F);
            l_szData[jj++] = l_stEncoding.charAt(((l_btTmp[0] & 0x03) << 4) | ((l_btTmp[1] >> 4) & 0x0F));
            l_szData[jj++] = l_stEncoding.charAt(((l_btTmp[1] & 0x0F) << 2) | ((l_btTmp[2] >> 6) & 0x03));
            l_szData[jj++] = l_stEncoding.charAt(l_btTmp[2] & 0x3F);
        }
        
        if (!l_bFlag)
        {
            l_btTmp[0] = btData[kk++];
            
            l_szData[jj++] = l_stEncoding.charAt((l_btTmp[0] >> 2) & 0x3F);
            l_szData[jj + 1] = '=';
            l_szData[jj + 2] = '=';
            
            if ((iLen % 3) == 1)
            {
                l_szData[jj] = l_stEncoding.charAt((l_btTmp[0] & 0x03) << 4);
            }
            else
            // if ((iLen % 3) == 2)
            {
                l_btTmp[1] = btData[kk];
                
                l_szData[jj++] = l_stEncoding.charAt(((l_btTmp[0] & 0x03) << 4) | ((l_btTmp[1] >> 4) & 0x0F));
                l_szData[jj] = l_stEncoding.charAt((l_btTmp[1] & 0x0F) << 2);
            }
        }
        
        return new String(l_szData);
    }
    
    public static byte[] getFromBASE64(String stData)
    {
        int l_iLen;
        int l_iGroup;
        int ii;
        int jj;
        int kk;
        boolean l_bFlag;
        char[] l_szTmp;
        byte[] l_btData = new byte[0];
        
        l_iLen = stData.length();
        
        if ((l_iLen % 4) != 0)
        {
            return l_btData;
        }
        
        l_iGroup = l_iLen / 4;
        ii = l_iGroup * 3;
        l_bFlag = true;
        l_szTmp = new char[4];
        
        if (stData.charAt(l_iLen - 1) == '=')
        {
            l_iLen--;
            ii--;
            l_iGroup--;
            
            l_bFlag = false;
            
            if (stData.charAt(l_iLen - 1) == '=')
            {
                l_iLen--;
                ii--;
            }
        }
        
        for (jj = 0; jj < l_iLen; jj++)
        {
            l_szTmp[0] = stData.charAt(jj);
            
            if (!((l_szTmp[0] == '+') || (('/' <= l_szTmp[0]) && (l_szTmp[0] <= '9'))
                || (('A' <= l_szTmp[0]) && (l_szTmp[0] <= 'Z')) || (('a' <= l_szTmp[0]) && (l_szTmp[0] <= 'z'))))
            {
                return l_btData;
            }
        }
        
        l_btData = new byte[ii];
        
        for (ii = 0, jj = 0, kk = 0; ii < l_iGroup; ii++)
        {
            l_szTmp[0] = returnToData(stData.charAt(kk++));
            l_szTmp[1] = returnToData(stData.charAt(kk++));
            l_szTmp[2] = returnToData(stData.charAt(kk++));
            l_szTmp[3] = returnToData(stData.charAt(kk++));
            
            l_btData[jj++] = (byte)((l_szTmp[0] << 2) | ((l_szTmp[1] >> 4) & 0x03));
            l_btData[jj++] = (byte)((l_szTmp[1] << 4) | ((l_szTmp[2] >> 2) & 0x0F));
            l_btData[jj++] = (byte)((l_szTmp[2] << 6) | (l_szTmp[3] & 0x3F));
        }
        
        if (!l_bFlag)
        {
            l_szTmp[0] = returnToData(stData.charAt(kk++));
            l_szTmp[1] = returnToData(stData.charAt(kk++));
            
            l_btData[jj++] = (byte)((l_szTmp[0] << 2) | ((l_szTmp[1] >> 4) & 0x03));
            
            if ((l_iLen % 4) == 3)
            {
                l_szTmp[2] = returnToData(stData.charAt(kk));
                
                l_btData[jj] = (byte)((l_szTmp[1] << 4) | ((l_szTmp[2] >> 2) & 0x0F));
            }
        }
        
        return l_btData;
    }
    
    private static char returnToData(char cChar)
    {
        if (('A' <= cChar) && (cChar <= 'Z'))
        {
            cChar -= 'A';
        }
        else if (('a' <= cChar) && (cChar <= 'z'))
        {
            cChar -= 'a';
            cChar += 26;
        }
        else if (('0' <= cChar) && (cChar <= '9'))
        {
            cChar -= '0';
            cChar += 52;
        }
        else if (cChar == '+')
        {
            cChar = 62;
        }
        else
        // if (cChar == '/')
        {
            cChar = 63;
        }
        
        return cChar;
    }
    
    public static String bytes2HexString(byte[] src)
    {
        StringBuilder stringBuilder = new StringBuilder("");
        if (null != src && src.length > 0)
        {
            for (int i = 0; i < src.length; i++)
            {
                int j = src[i] & 0xFF;
                String str = Integer.toHexString(j);
                if (str.length() < 2)
                {
                    stringBuilder.append(0);
                }
                stringBuilder.append(str);
            }
            return stringBuilder.toString();
        }
        return null;
    }
}
