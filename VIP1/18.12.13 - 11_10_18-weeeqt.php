<?php exit() ?>--by weeeqt 188.134.66.28
function ClientSide:ToggleUnitHPBar(unit, toggle)
    if unit and unit.valid then
        local pE = CLoLPacket(0xCE)
        pE:EncodeF(unit.networkID)
        pE:Encode1(toggle and 1 or 0)
        RecvPacket(pE)
    end
end