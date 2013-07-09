<!DOCTYPE html>
<html lang="en">
<head>
	<script src="flowplayer-3.2.12.min.js"></script>
	<script src="flowplayer-controller-config.js"></script>
	<title>Racktube is playing: <?php echo $_POST["filename"];?> from Akamai</title>
</head>
<body>
	
<div style="width:640px;height:360px;" id="player" ></div>

<script language="JavaScript">
var jp = $f
	(
		"player",
		{
			src: "flowplayer-v3.2.16.swf"
		},		
		{ 
	    	debug : true,
			/*
   		    streamCallbacks: ['onFI'],
			onError:function(errorCode, errorMessage) 
			{
				alert("Flow error"+ errorCode + " Message = "+ errorMessage);
			},			
		 	onFI:function(clip, info)
			{
				console.log("onFI"+info);
		    },*/
	    	clip : 
	     	{
				live:true,				
				provider: 'akamai',
				autoPlay: false,
				stopLiveOnPause:false,
				url:'<?php echo $_POST["url"];?>'
			},
			/*playlist: 
			[                                                                                             			   
				'http://multiformatlive-f.akamaihd.net/z/demostream_1@2131/manifest.f4m'                         		   
				'http://zerihdndemo-f.akamaihd.net/z/h264/darkknight/darkknight.smil/manifest.f4m',                  		   
				'http://mediapm.edgesuite.net/edgeflash/public/debug/assets/smil/buckbunny-vod.smil',          			   
				'http://mediapm.edgesuite.net/edgeflash/public/debug/assets/smil/nelly2.smil',                 			   
				'http://mediapm.edgesuite.net/osmf/content/test/smil/elephants_dream.smil'
				'rtmp://cp67126.edgefcs.net/ondemand/mp4:mediapm/osmf/content/test/akamai_10_year_500.mov'     			   
			],*/                                                                                             		   
 			plugins: 
 			{			
				controls:controlObject, 
           		akamai:
           		{
					url:'AkamaiFlowPlugin.swf'
					
					//<----------------- HDN 1.0 CONFIG TAGS ---------------------------->
					// , startingBufferTime:5
					// , forceNoSubclip:true
					// , useMBRStartupBandwidthCheck:{duration:3}
					// , mbrObject:
					// 	[                                                                                  
					// 	{src:"nelly2_h264_300@14411",  width:640, height:360, bitrate:"300"},               				
					// 	{src:"nelly2_h264_700@14411",  width:640, height:360, bitrate:"700"},              
					// 	{src:"nelly2_h264_1500@14411",  width:640, height:360, bitrate:"1500"},            
					// 	{src:"nelly2_h264_2500@14411",  width:640, height:360, bitrate:"2500"},            
					// 	{src:"nelly2_h264_3500@14411",  width:640, height:360, bitrate:"3500"}             
					// 	]                                                                                  
					//, primaryToken:'1336218178_ee70588889d6e859ffcd58c49c3872be'
					// tokenService:{url:escaped(token generation url here)'}
					//<----------------- HDN 2.0 CONFIG TAGS ---------------------------->	
					//, addManifestQueryArgsToFragmentRequests:false
					//, enableLogStringOnFragments:false					
					//, enableLargeBuffersForLongFormContent:false
					//, fragmentRetryAttemptsForLostConnectivity:120
					//, liveBufferProfile:'livelowlatency' // OR 'livestable' 
					//, netsessionMode:'opportunistic'  //OR 'never'									
					//<----------------- HDN 1.0 & 2.0 CONFIG TAGS ---------------------------->
					//, enableAlternateServerMapping:false
					//, enableEndUserMapping:false					
					//<----------------- RTMP CONFIG TAGS ---------------------------->
					//, retryLive:true
					//, retryInterval:5
					//, liveTimeout:5
					//, connectionAttemptInterval:5
					//, connectAuthParams:'connectionAuthToken'
					//, streamAuthParams:'auth=livestreamAuthToken'
					//<----------------- Works on all network types ---------------------------->
					//, autoRewind:false
					//, subClip:{clipBegin:90, clipEnd:300}
					//, mbrStartingBitrate:3500
					//, mbrStartingIndex:2
					//, akamaiMediaType:'akamai-hdn-single-bitrate'	
					//, akamaiMediaData:{}
					//, genericNetStreamProperties:[{propertyName:'enableAlternateServerMapping', value:true}, {propertyName:'netSessionMode', value:'never'}]
					//, genericNetStreamProperty:{propertyName:'netSessionMode', value:'never'}
           		}
   			}
 		}	
	);
</script>
</body>
</html>
