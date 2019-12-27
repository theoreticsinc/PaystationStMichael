/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package misc;


import java.util.Map;
import java.security.MessageDigest;

import org.apache.commons.httpclient.HttpConstants;
import org.apache.commons.httpclient.Credentials;
import org.apache.commons.httpclient.UsernamePasswordCredentials;
import org.apache.commons.logging.Log;
import org.apache.commons.logging.LogFactory;
import org.apache.http.Header;
import org.apache.http.HttpRequest;
import org.apache.http.auth.AuthenticationException;
import org.apache.http.impl.auth.RFC2617Scheme;

/**
 * 

 * Digest authentication scheme as defined in RFC 2617.
 * 


 * 
 * @author Remy Maucherat
 * @author Rodney Waldhoff
 * @author Jeff Dever
 * @author Ortwin Glück
 * @author Sean C. Sullivan
 * @author Adrian Sutton
 * @author Mike Bowler
 * @author Oleg Kalnichevski
 */

public class DigestScheme extends RFC2617Scheme {
    
    /** Log object for this class. */
    private static final Log LOG = LogFactory.getLog(DigestScheme.class);

    /**
     * Hexa values used when creating 32 character long digest in HTTP DigestScheme
     * in case of authentication.
     * 
     * @see #encode(byte[])
     */
    private static final char[] HEXADECIMAL = {
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 
        'e', 'f'
    };

    /**
     * Gets an ID based upon the realm and the nonce value.  This ensures that requests
     * to the same realm with different nonce values will succeed.  This differentiation
     * allows servers to request re-authentication using a fresh nonce value.
     * 
     * @return the realm plus the nonce value, if present
     */
    public String getID() {
        
        String id = getRealm();
        String nonce = getParameter("nonce");
        if (nonce != null) {
            id += "-" + nonce;
        }
        
        return id;
    }

    /**
     * Constructor for the digest authentication scheme.
     * 
     * @param challenge The authentication challenge
     * 
     * @throws MalformedChallengeException is thrown if the authentication challenge
     * is malformed
     */
//    public DigestScheme(final String challenge) 
//      throws MalformedChallengeException {
//        super(challenge);
//        if (this.getParameter("realm") == null) {
//            throw new MalformedChallengeException("realm missing");
//        }
//        if (this.getParameter("nonce") == null) {
//            throw new MalformedChallengeException("nonce missing");
//        }
//        this.getParameters().put("nc", "00000001");
//    }


    /**
     * Returns textual designation of the digest authentication scheme.
     * 
     * @return digest
     */
    public String getSchemeName() {
        return "digest";
    }

    /**
     * Produces a digest authorization string for the given set of 
     * {@link Credentials}, method name and URI.
     * 
     * @param credentials A set of credentials to be used for athentication
     * @param method the name of the method that requires authorization. 
     * @param uri The URI for which authorization is needed. 
     * 
     * @throws AuthenticationException if authorization string cannot 
     *   be generated due to an authentication failure
     * 
     * @return a digest authorization string
     * 
     * @see org.apache.commons.httpclient.HttpMethod#getName()
     * @see org.apache.commons.httpclient.HttpMethod#getPath()
     */
    public String authenticate(Credentials credentials, String method, String uri)
      throws AuthenticationException {

        LOG.trace("enter DigestScheme.authenticate(Credentials, String, String)");

        UsernamePasswordCredentials usernamepassword = null;
        try {
            usernamepassword = (UsernamePasswordCredentials) credentials;
        } catch (ClassCastException e) {
            throw new AuthenticationException(
             "Credentials cannot be used for digest authentication: " 
              + credentials.getClass().getName());
        }
        this.getParameters().put("cnonce", createCnonce());
        this.getParameters().put("methodname", method);
        this.getParameters().put("uri", uri);
        return DigestScheme.authenticate(usernamepassword, getParameters());
    }

    /**
     * Produces a digest authorization string for the given set of 
     * {@link UsernamePasswordCredentials} and authetication parameters.
     *
     * @param credentials Credentials to create the digest with
     * @param params The authetication parameters. The following
     *  parameters are expected: uri, realm, 
     *  nonce, nc, cnonce, 
     *  qop, methodname.
     * 
     * @return a digest authorization string
     * 
     * @throws AuthenticationException if authorization string cannot 
     *   be generated due to an authentication failure
     */
    public static String authenticate(UsernamePasswordCredentials credentials,
            Map params) throws AuthenticationException {

        LOG.trace("enter DigestScheme.authenticate(UsernamePasswordCredentials, Map)");

        String digest = createDigest(credentials.getUserName(),
                credentials.getPassword(), params);

        return "Digest " + createDigestHeader(credentials.getUserName(),
                params, digest);
    }

    /**
     * Creates an MD5 response digest.
     * 
     * @param uname Username
     * @param pwd Password
     * @param params The parameters necessary to construct the digest. 
     *  The following parameters are expected: uri, 
     *  realm, nonce, nc, 
     *  cnonce, qop, methodname.
     * 
     * @return The created digest as string. This will be the response tag's
     *         value in the Authentication HTTP header.
     * @throws AuthenticationException when MD5 is an unsupported algorithm
     */
    public static String createDigest(String uname, String pwd,
            Map params) throws AuthenticationException {

        LOG.trace("enter DigestScheme.createDigest(String, String, Map)");

        final String digAlg = "MD5";

        // Collecting required tokens
        String uri = (String) params.get("uri");
        String realm = (String) params.get("realm");
        String nonce = (String) params.get("nonce");
        String nc = (String) params.get("nc");
        String cnonce = (String) params.get("cnonce");
        String qop = (String) params.get("qop");
        String method = (String) params.get("methodname");
        String algorithm = (String) params.get("algorithm");

        // If an algorithm is not specified, default to MD5.
        if(algorithm == null) {
            algorithm="MD5";
        }

        if (qop != null) {
            qop = "auth";
        }

        MessageDigest md5Helper;

        try {
            md5Helper = MessageDigest.getInstance(digAlg);
        } catch (Exception e) {
            throw new AuthenticationException(
              "Unsupported algorithm in HTTP Digest authentication: "
               + digAlg);
        }

        // Calculating digest according to rfc 2617

        String a1 = null;
        if(algorithm.equals("MD5")) {
            // unq(username-value) ":" unq(realm-value) ":" passwd
            a1 = uname + ":" + realm + ":" + pwd;
        } else if(algorithm.equals("MD5-sess")) {
            // H( unq(username-value) ":" unq(realm-value) ":" passwd )
            //      ":" unq(nonce-value)
            //      ":" unq(cnonce-value)

            String tmp=encode(md5Helper.digest(HttpConstants.getContentBytes(
                uname + ":" + realm + ":" + pwd)));

            a1 = tmp + ":" + nonce + ":" + cnonce;
        } else {
            LOG.warn("Unhandled algorithm " + algorithm + " requested");
            a1 = uname + ":" + realm + ":" + pwd;
        }
        String md5a1 = encode(md5Helper.digest(HttpConstants.getContentBytes(a1)));
        String serverDigestValue;

        String a2 = method + ":" + uri;
        String md5a2 = encode(md5Helper.digest(HttpConstants.getBytes(a2)));

        if (qop == null) {
            LOG.debug("Using null qop method");
            serverDigestValue = md5a1 + ":" + nonce + ":" + md5a2;
        } else {
            LOG.debug("Using qop method " + qop);
            serverDigestValue = md5a1 + ":" + nonce + ":" + nc + ":" + cnonce
                                + ":" + qop + ":" + md5a2;
        }

        String serverDigest =
            encode(md5Helper.digest(HttpConstants.getBytes(serverDigestValue)));

        return serverDigest;
    }

    /**
     * Creates digest-response header as defined in RFC2617.
     * 
     * @param uname Username
     * @param params The parameters necessary to construct the digest header. 
     *  The following parameters are expected: uri, 
     *  realm, nonce, nc, 
     *  cnonce, qop, methodname.
     * @param digest The response tag's value as String.
     * 
     * @return The digest-response as String.
     */
    public static String createDigestHeader(String uname, Map params,
            String digest) {

        LOG.trace("enter DigestScheme.createDigestHeader(String, Map, "
            + "String)");

        StringBuffer sb = new StringBuffer();
        String uri = (String) params.get("uri");
        String realm = (String) params.get("realm");
        String nonce = (String) params.get("nonce");
        String nc = (String) params.get("nc");
        String cnonce = (String) params.get("cnonce");
        String opaque = (String) params.get("opaque");
        String response = digest;
        String qop = (String) params.get("qop");
        String algorithm = (String) params.get("algorithm");

        if (qop != null) {
            qop = "auth"; //we only support auth
        }

        sb.append("username=\"" + uname + "\"")
          .append(", realm=\"" + realm + "\"")
          .append(", nonce=\"" + nonce + "\"")
          .append(", uri=\"" + uri + "\"")
          .append(((qop == null) ? "" : ", qop=\"" + qop + "\""))
          .append((algorithm == null) ? "" : ", algorithm=\"" + algorithm + "\"")
          .append(((qop == null) ? "" : ", nc=" + nc))
          .append(((qop == null) ? "" : ", cnonce=\"" + cnonce + "\""))
          .append(", response=\"" + response + "\"")
          .append((opaque == null) ? "" : ", opaque=\"" + opaque + "\"");

        return sb.toString();
    }


    /**
     * Encodes the 128 bit (16 bytes) MD5 digest into a 32 characters long 
     * String according to RFC 2617.
     * 
     * @param binaryData array containing the digest
     * @return encoded MD5, or null if encoding failed
     */
    private static String encode(byte[] binaryData) {
        LOG.trace("enter DigestScheme.encode(byte[])");

        if (binaryData.length != 16) {
            return null;
        } 

        char[] buffer = new char[32];
        for (int i = 0; i < 16; i++) {
            int low = (int) (binaryData[i] & 0x0f);
            int high = (int) ((binaryData[i] & 0xf0) >> 4);
            buffer[i * 2] = HEXADECIMAL[high];
            buffer[(i * 2) + 1] = HEXADECIMAL[low];
        }

        return new String(buffer);
    }


    /**
     * Creates a random cnonce value based on the current time.
     * 
     * @return The cnonce value as String.
     * @throws AuthenticationException if MD5 algorithm is not supported.
     */
    public static String createCnonce() throws AuthenticationException {
        LOG.trace("enter DigestScheme.createCnonce()");

        String cnonce;
        final String digAlg = "MD5";
        MessageDigest md5Helper;

        try {
            md5Helper = MessageDigest.getInstance(digAlg);
        } catch (Exception e) {
            throw new AuthenticationException(
              "Unsupported algorithm in HTTP Digest authentication: "
               + digAlg);
        }

        cnonce = Long.toString(System.currentTimeMillis());
        cnonce = encode(md5Helper.digest(HttpConstants.getBytes(cnonce)));

        return cnonce;
    }

    @Override
    public boolean isConnectionBased() {
        throw new UnsupportedOperationException("Not supported yet."); //To change body of generated methods, choose Tools | Templates.
    }

    @Override
    public boolean isComplete() {
        throw new UnsupportedOperationException("Not supported yet."); //To change body of generated methods, choose Tools | Templates.
    }

    @Override
    public Header authenticate(org.apache.http.auth.Credentials c, HttpRequest hr) throws AuthenticationException {
        throw new UnsupportedOperationException("Not supported yet."); //To change body of generated methods, choose Tools | Templates.
    }
}