<?php exit() ?>--by dienofail 68.48.159.9
if VIP_USER then

 local NoFaceXetrok

 NoFaceXetrok = function(spell, param, param2)

  if param and param2 then
   Packet("S_CAST", {spellId = spell, fromX = param, fromY = param2}):send()
  end
  elseif param then
   Packet("S_CAST", {spellId = spell, targetNetworkId = param.networkID}):send()
  else
   Packet("S_CAST", {spellId = spell}):send()
  end

  return true

 end

 else
  NoFaceXetrok = CastSpell
 end
end