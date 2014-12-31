<?php exit() ?>--by pqmailer 217.82.39.147
_G.CastSpell = function(spellId, x1, z1, x2, z2, targetNetworkId)
    local p = CLoLPacket(0x99)
    p.dwArg1 = 1
    p.dwArg2 = 0
    p:EncodeF(myHero.networkID)
    p:Encode1(spellId or 0)
    p:EncodeF(x1 or mousePos.x)
    p:EncodeF(z1 or mousePos.z)
    p:EncodeF(x2 or mousePos.x)
    p:EncodeF(z2 or mousePos.z)
    p:EncodeF(targetNetworkId or 0)
    SendPacket(p)
end