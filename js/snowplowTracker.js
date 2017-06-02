
window.snowplow('newTracker', 'cf',sp_data.collector, {"appId":sp_data.application_id, "platform":"web", "cookieDomain":".practiceweb.co.uk", "contexts":{"webPage":true, "gaCookies":true, "performanceTiming":true}, "post":true});
window.snowplow('enableActivityTracking', 30, 30);
window.snowplow('enableLinkClickTracking');
window.snowplow('trackPageView');