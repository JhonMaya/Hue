<?php exit() ?>--by ferrarino1com 88.11.183.222
_G.l33tultr4s3cr3tXpl01t = function (p1, p2, p3)
if p3 then
Packet("S_CAST", {spellId = p1, toX = p2.x, toY = p3.z}):send()
else
Packet("S_CAST", {spellId = p1, targetNetworkId = p2}):send()
end
end